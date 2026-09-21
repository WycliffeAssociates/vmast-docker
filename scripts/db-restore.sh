#!/usr/bin/env bash
#
# Restores a mariadb-backup set, orchestrating the parts that have to happen
# outside the database container.
#
# mariadb-backup --copy-back needs the server stopped and the data directory
# empty, so this stops the db service, runs db_restore.sh in a throwaway
# container holding both volumes, and starts the service again.
#
#   ./scripts/db-restore.sh --list
#   ./scripts/db-restore.sh                    # active set, latest increment
#   ./scripts/db-restore.sh --hour 5           # active set, up to increment 5
#   ./scripts/db-restore.sh --set set-2026-09-20_02-00-00.tar.gz --hour 0
#   ./scripts/db-restore.sh --from-dump            # newest logical dump
#   ./scripts/db-restore.sh --from-dump vmast-2026-09-21_03-00-00.sql.gz
#   ./scripts/db-restore.sh --prod -y
#
# A physical restore replaces the data directory and needs the server stopped.
# --from-dump is SQL into a running server: no downtime, no stop and start.
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

PROD_ONLY=0
ASSUME_YES=0
FROM_DUMP=0
BACKUP=""
SET=""
HOUR=""
LIST=0

say()  { printf '\033[1;34m==>\033[0m %s\n' "$*"; }
warn() { printf '\033[1;33mwarn:\033[0m %s\n' "$*" >&2; }
die()  { printf '\033[1;31merror:\033[0m %s\n' "$*" >&2; exit 1; }

usage() { sed -n '2,/^set -euo/p' "${BASH_SOURCE[0]}" | sed 's/^# \{0,1\}//; $d'; exit 0; }

while [[ $# -gt 0 ]]; do
    case "$1" in
        --list)      LIST=1 ;;
        --from-dump) FROM_DUMP=1 ;;
        --set)     SET="${2:?--set needs a name}"; shift ;;
        --hour)    HOUR="${2:?--hour needs a number}"; shift ;;
        --prod)    PROD_ONLY=1 ;;
        -y|--yes)  ASSUME_YES=1 ;;
        -h|--help) usage ;;
        -*)        die "Unknown option: $1 (try --help)" ;;
        *)         BACKUP="$1" ;;
    esac
    shift
done

COMPOSE_FILES=(-f "$ROOT/docker-compose.yml")
if [[ $PROD_ONLY -eq 0 && -f "$ROOT/compose.local.yml" ]]; then
    COMPOSE_FILES+=(-f "$ROOT/compose.local.yml")
fi

compose() { docker compose "${COMPOSE_FILES[@]}" "$@"; }

command -v docker >/dev/null 2>&1 || die "docker is required but not installed."

# The db-backup image carries the scripts and the mariadb-backup binary.
# Compose leaves the "image" key unset for services it builds, so ask it what
# the service is actually running rather than parsing the config; fall back to
# the name compose gives a built image.
IMAGE=$(compose images -q db-backup 2>/dev/null | head -1)

if [[ -z "$IMAGE" ]]; then
    IMAGE="${COMPOSE_PROJECT_NAME:-$(basename "$ROOT")}-db-backup"

    docker image inspect "$IMAGE" >/dev/null 2>&1 \
        || die "Cannot find the db-backup image. Build it first:
       docker compose build db-backup"
fi

say "Using image $IMAGE"

# The volumes, resolved rather than assumed.
resolve_volume() {
    local key="$1" name
    name=$(compose config --format json 2>/dev/null | tr -d ' \n' \
        | sed -n "s/.*\"$key\":{\"name\":\"\([^\"]*\)\".*/\1/p" | head -1)
    [[ -n "$name" ]] || name="${COMPOSE_PROJECT_NAME:-$(basename "$ROOT")}_$key"
    printf '%s' "$name"
}

DBDATA="$(resolve_volume dbdata)"
docker volume inspect "$DBDATA" >/dev/null 2>&1 || die "No volume $DBDATA - has the stack ever run?"

# A named volume in both environments: on macOS a bind mount crosses into the
# Docker VM's filesystem, and a restore copies a 1.4GB base through it before
# it can prepare anything.
BACKUP_MOUNT="$(resolve_volume db_backups)"

docker volume inspect "$BACKUP_MOUNT" >/dev/null 2>&1 \
    || die "No volume $BACKUP_MOUNT - nothing has been backed up yet."

run_in_sidecar() {
    local script="$1"; shift

    docker run --rm -i \
        --platform linux/amd64 \
        --entrypoint "$script" \
        -v "$DBDATA:/var/lib/mysql" \
        -v "$BACKUP_MOUNT:/backup" \
        "$IMAGE" "$@"
}

run_in_container() {
    compose exec -T db-backup "$@"
}

if [[ $LIST -eq 1 ]]; then
    printf '\033[1mPhysical sets\033[0m (point-in-time, needs a restart)\n'
    run_in_sidecar db_restore.sh --list || true

    printf '\033[1mLogical dumps\033[0m (--from-dump, no downtime)\n'
    run_in_container db_dump_restore.sh --list || true

    exit 0
fi

# A logical restore goes into the running server, so it runs in the live
# db-backup container rather than a sidecar, and nothing is stopped.
if [[ $FROM_DUMP -eq 1 ]]; then
    # Confirmed here rather than in the container: compose exec -T has no
    # terminal, so a prompt inside would have nothing to read from.
    if [[ $ASSUME_YES -eq 0 ]]; then
        printf '\033[1;33mThis DROPS the database and replaces it with %s.\033[0m\n' \
            "${BACKUP:-the newest dump}"
        read -r -p 'Type ERASE to continue: ' reply
        [[ "$reply" == "ERASE" ]] || die "Aborted."
    fi

    say "Restoring from a logical dump; the database stays up"

    dump_args=(-y)
    [[ -n "$BACKUP" ]] && dump_args+=("$BACKUP")

    run_in_container db_dump_restore.sh "${dump_args[@]}"

    exit 0
fi

if [[ $ASSUME_YES -eq 0 ]]; then
    printf '\033[1;33mThis stops the database, ERASES its data directory, and restores %s.\033[0m\n' \
        "${SET:-the active set}${HOUR:+ up to increment $HOUR}"
    read -r -p 'Type ERASE to continue: ' reply
    [[ "$reply" == "ERASE" ]] || die "Aborted."
fi

say "Stopping the db service"
compose stop db

# From here the data directory is being replaced; leaving the service down on
# failure is deliberate, since starting a server on a half-copied datadir is
# worse than an outage.
say "Restoring inside a throwaway container"

if run_in_sidecar db_restore.sh -y ${SET:+--set "$SET"} ${HOUR:+--hour "$HOUR"} ${BACKUP:+"$BACKUP"}; then
    say "Starting the db service"
    compose start db
    say "Done. Give the server a few seconds, then check: compose logs db"
else
    warn "The restore failed and the db service has been left stopped on purpose."
    die "Fix the backup or restore a different set before starting it again."
fi
