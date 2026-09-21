#!/usr/bin/env bash
#
# Pulls the production database and the user-created assets into the local
# Docker stack.
#
#   Database: mysqldump runs on the production host and the gzip is streamed
#             back, so only SSH has to be reachable. Production is MySQL 5.5
#             and the container is MariaDB 10.5, so this is a logical dump -
#             a physical xtrabackup set cannot cross that gap.
#
#   Assets:   the source directory is tarred on the production host and piped
#             straight into tar here, so no 100MB+ archive is parked on either
#             side unless --keep-archive asks for one.
#
# Configure it with scripts/.env.sync (see .env.sync.example), then:
#
#   ./scripts/sync-from-production.sh                 # database and assets
#   ./scripts/sync-from-production.sh --db-only
#   ./scripts/sync-from-production.sh --assets-only --push
#   ./scripts/sync-from-production.sh --assets-only --archive ~/Desktop/vmast_backups/source.tar.gz
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

# Absolute paths, so the script works from any working directory.
DB_SERVICE="db"

DUMP_DIR="$ROOT/db/dump"
DUMP_FILE="$DUMP_DIR/production.sql.gz"

ASSETS_PARENT="$ROOT/php/htdocs/webapp/www/app/Templates/Default/Assets"
FONTS_UPLOADS="$ASSETS_PARENT/fonts/uploads"

# Production still keeps these under Assets/source; locally they live in the
# framework's storage directory, so the archive's leading source/ is stripped
# rather than preserved.
RESOURCES="$ROOT/php/htdocs/webapp/www/app/Storage/Resources"

# Only these two are user content; everything else in fonts/ ships in the image.
FONT_FILES=(SUN.woff BackSUN.woff)
CONTAINER_RESOURCES="/usr/share/nginx/html/app/Storage/Resources"

# Production is MySQL 5.5 and every table is InnoDB utf8, so: --single-transaction
# for a consistent dump without locking, utf8 to match the tables, and no
# --routines/--triggers, which 5.5 gates behind extra privileges and this schema
# has none of anyway.
DUMP_OPTS=(--single-transaction --quick --default-character-set=utf8)

# The tables declare CHARSET=utf8 COLLATE=utf8_unicode_ci; the local database is
# created to match, so tables the app creates later agree with the ones imported.
DB_CHARSET="utf8"
DB_COLLATE="utf8_unicode_ci"

DO_DB=1
DO_ASSETS=1
DO_FONTS=1
DO_PUSH=0
KEEP_ARCHIVE=0
LOCAL_ARCHIVE=""
SKIP_DUMP=0
ASSUME_YES=0
ASK_PASS=0
SEED_VOLUME=0
PROD_ONLY=0
VOLUME_NAME=""
SEED_IMAGE="${SEED_IMAGE:-alpine:3}"

say()  { printf '\033[1;34m==>\033[0m %s\n' "$*"; }
warn() { printf '\033[1;33mwarn:\033[0m %s\n' "$*" >&2; }
die()  { printf '\033[1;31merror:\033[0m %s\n' "$*" >&2; exit 1; }

usage() {
    sed -n '2,/^set -euo/p' "${BASH_SOURCE[0]}" | sed 's/^# \{0,1\}//; $d'
    cat <<'EOF'
Options:
  --db-only            Only refresh the database
  --assets-only        Only refresh the assets
  --fonts-only         Only refresh the admin-uploaded fonts
  --no-fonts           Skip the fonts
  --skip-dump          Import the dump already at db/dump/production.sql.gz
  --archive PATH       Unpack this local source.tar.gz instead of fetching
                       it from production
  --keep-archive       Also save the fetched assets as a .tar.gz next to the dump
  --push               Copy the assets into the running web and php containers
                       as well, rather than waiting for a watch sync or rebuild
  --seed-volume        Unpack into the app_storage and fonts_uploads named
                       volumes rather than the working tree. For a first
                       production deploy; the stack may be stopped.
  --prod               Use only docker-compose.yml, ignoring compose.local.yml
  --volume-name NAME   Target this volume instead of the resolved one
  --ask-pass           Authenticate interactively instead of requiring a key.
                       The connection is reused, so the password is typed once
                       however many commands the run makes.
  -y, --yes            Do not ask before replacing anything
  -h, --help           This message
EOF
    exit 0
}

while [[ $# -gt 0 ]]; do
    case "$1" in
        --db-only)      DO_ASSETS=0; DO_FONTS=0 ;;
        --assets-only)  DO_DB=0; DO_FONTS=0 ;;
        --fonts-only)   DO_DB=0; DO_ASSETS=0 ;;
        --no-fonts)     DO_FONTS=0 ;;
        --skip-dump)    SKIP_DUMP=1 ;;
        --archive)      LOCAL_ARCHIVE="${2:?--archive needs a path}"; shift ;;
        --keep-archive) KEEP_ARCHIVE=1 ;;
        --push)         DO_PUSH=1 ;;
        --seed-volume)  SEED_VOLUME=1 ;;
        --prod)         PROD_ONLY=1 ;;
        --volume-name)  VOLUME_NAME="${2:?--volume-name needs a name}"; shift ;;
        --ask-pass)     ASK_PASS=1 ;;
        -y|--yes)       ASSUME_YES=1 ;;
        -h|--help)      usage ;;
        *)              die "Unknown option: $1 (try --help)" ;;
    esac
    shift
done

# compose.local.yml carries the development overrides - bind mounts in place of
# the named volumes - so production must not load it. Built here rather than
# above because it depends on --prod having been parsed.
COMPOSE_FILES=(-f "$ROOT/docker-compose.yml")

if [[ $PROD_ONLY -eq 0 && -f "$ROOT/compose.local.yml" ]]; then
    COMPOSE_FILES+=(-f "$ROOT/compose.local.yml")
fi

# ---------------------------------------------------------------- configuration

[[ -f "$ROOT/scripts/.env.sync" ]] \
    || die "Missing scripts/.env.sync - copy scripts/.env.sync.example and fill it in."

# shellcheck disable=SC1091
set -a; source "$ROOT/scripts/.env.sync"; set +a

[[ -f "$ROOT/.env" ]] || die "Missing .env - the local database credentials come from it."
# shellcheck disable=SC1091
set -a; source "$ROOT/.env"; set +a

PROD_SSH_PORT="${PROD_SSH_PORT:-22}"
PROD_DB_HOST="${PROD_DB_HOST:-127.0.0.1}"
PROD_DB_PORT="${PROD_DB_PORT:-3306}"

SSH_OPTS=(-p "$PROD_SSH_PORT")

# Deliberately under /tmp rather than $TMPDIR: a control socket path may not
# exceed 104 bytes, macOS sets TMPDIR to a ~52 byte path, and ssh adds the
# 40 character %C hash plus a temporary suffix of its own on top.
RUNTIME_DIR=""

make_runtime_dir() {
    [[ -n "$RUNTIME_DIR" ]] && return 0

    RUNTIME_DIR="$(mktemp -d /tmp/vmast.XXXXXX)"

    # Removed on any exit, including an interrupt.
    trap 'rm -rf "$RUNTIME_DIR"' EXIT INT TERM
}

if [[ $ASK_PASS -eq 1 ]]; then
    make_runtime_dir

    # Multiplex over a single authenticated connection, so the password is
    # entered once rather than for every ssh this run makes.
    SSH_OPTS+=(
        -o ControlMaster=auto
        -o ControlPath="$RUNTIME_DIR/cm-%C"
        -o ControlPersist=120
    )
else
    # Without this a missing key waits on a prompt that never comes in a pipe.
    SSH_OPTS+=(-o BatchMode=yes)
fi

[[ -n "${PROD_SSH_KEY:-}" ]] && SSH_OPTS+=(-i "$PROD_SSH_KEY")

require_vars() {
    for name in "$@"; do
        [[ -n "${!name:-}" ]] || die "$name is not set in scripts/.env.sync"
    done
}

# Credentials are asked for rather than kept in a file. Anything already set in
# the environment or in .env.sync is used as-is, so an unattended run can still
# supply them.
have_tty() {
    ( printf '' >/dev/tty ) 2>/dev/null
}

prompt_var() {
    local name="$1" label="$2"

    [[ -n "${!name:-}" ]] && return 0

    have_tty || die "$label is needed. Set $name in the environment for an unattended run."

    read -r -p "$label: " "$name" </dev/tty
    [[ -n "${!name:-}" ]] || die "$label cannot be empty."
}

# Echoes an asterisk per character instead of leaving the line blank, so it is
# visible that the input is being taken. bash 3.2 has no namerefs, so the
# variable is filled through read.
read_masked() {
    local name="$1" label="$2" ch value=""

    [[ -n "${!name:-}" ]] && return 0

    have_tty || die "$label is needed. Set $name in the environment for an unattended run."

    printf '%s: ' "$label" >/dev/tty

    while IFS= read -r -s -n1 ch </dev/tty 2>/dev/null; do
        case "$ch" in
            '')
                break
                ;;
            $'\177'|$'\b')
                if [[ -n "$value" ]]; then
                    value="${value%?}"
                    printf '\b \b' >/dev/tty
                fi
                ;;
            *)
                value="$value$ch"
                printf '*' >/dev/tty
                ;;
        esac
    done

    printf '\n' >/dev/tty

    [[ -n "$value" ]] || die "$label cannot be empty."

    IFS= read -r "$name" <<< "$value"
}

command -v docker >/dev/null 2>&1 || die "docker is required but not installed."

compose() { docker compose "${COMPOSE_FILES[@]}" "$@"; }

prod_ssh() {
    command -v ssh >/dev/null 2>&1 || die "ssh is required but not installed."
    require_vars PROD_SSH_HOST PROD_SSH_USER

    ssh "${SSH_OPTS[@]}" "$PROD_SSH_USER@$PROD_SSH_HOST" "$@"
}

# Everything that has to be typed is collected here, in the main shell and
# before any work starts: the steps below run inside pipelines, where a prompt
# would both come at a confusing moment and be lost with the subshell.
collect_credentials() {
    local needs_ssh=0

    [[ $DO_DB -eq 1 && $SKIP_DUMP -eq 0 ]] && needs_ssh=1
    [[ $DO_ASSETS -eq 1 && -z "$LOCAL_ARCHIVE" ]] && needs_ssh=1
    [[ $DO_FONTS -eq 1 ]] && needs_ssh=1

    if [[ $needs_ssh -eq 1 ]]; then
        require_vars PROD_SSH_HOST

        prompt_var PROD_SSH_USER "Enter username"

        if [[ $ASK_PASS -eq 1 ]]; then
            read_masked PROD_SSH_PASS "Enter your password"

            setup_askpass
        fi
    fi

    if [[ $DO_DB -eq 1 && $SKIP_DUMP -eq 0 ]]; then
        require_vars PROD_DB_NAME PROD_DB_USER

        read_masked PROD_DB_PASS "Enter database password"
    fi
}

# Hands the password to ssh through an askpass helper. The helper carries no
# secret itself - it reads one from its inherited environment - so nothing
# sensitive is written to disk.
setup_askpass() {
    if ! ssh-keygen -F "$PROD_SSH_HOST" >/dev/null 2>&1; then
        die "The host key for $PROD_SSH_HOST is not known yet. Connect once with
       'ssh $PROD_SSH_USER@$PROD_SSH_HOST' to verify and record it; otherwise ssh
       asks for confirmation and the helper would answer with the password."
    fi

    make_runtime_dir

    cat > "$RUNTIME_DIR/askpass" <<'ASKPASS'
#!/bin/sh
printf '%s\n' "$VMAST_SSH_PASS"
ASKPASS
    chmod 700 "$RUNTIME_DIR/askpass"

    export VMAST_SSH_PASS="$PROD_SSH_PASS"
    export SSH_ASKPASS="$RUNTIME_DIR/askpass"
    export SSH_ASKPASS_REQUIRE=force
}

running_db() {
    local cid
    cid=$(compose ps -q "$DB_SERVICE" 2>/dev/null || true)

    [[ -n "$cid" ]] && [[ "$(docker inspect -f '{{.State.Running}}' "$cid" 2>/dev/null)" == "true" ]] \
        || die "The '$DB_SERVICE' service is not running - start the stack with 'make local' first."
}

confirm() {
    [[ $ASSUME_YES -eq 1 ]] && return 0

    have_tty || die "This step needs confirmation; pass -y for an unattended run."

    read -r -p "$1 [y/N] " reply </dev/tty
    [[ "$reply" == "y" || "$reply" == "Y" ]]
}

# ------------------------------------------------------------------- the dump

dump_database() {
    mkdir -p "$DUMP_DIR"

    local tmp="$DUMP_FILE.partial"

    say "Dumping $PROD_DB_NAME on $PROD_SSH_HOST (gzipped on the server)"

    # The password travels on the ssh session's stdin and is read into the
    # remote shell's environment, so it appears in no command line - not in the
    # local ssh process, not in the remote process list. Everything else here is
    # non-secret and interpolated.
    printf '%s\n' "$PROD_DB_PASS" \
        | prod_ssh "read -r MYSQL_PWD; export MYSQL_PWD; mysqldump \
            -h $(printf '%q' "$PROD_DB_HOST") -P $(printf '%q' "$PROD_DB_PORT") \
            -u $(printf '%q' "$PROD_DB_USER") \
            ${DUMP_OPTS[*]} $(printf '%q' "$PROD_DB_NAME") | gzip -c" > "$tmp" \
        || { rm -f "$tmp"; die "The dump failed. If the error above is from ssh rather than
       mysqldump, it is authentication: install a key with 'ssh-copy-id
       ${PROD_SSH_USER:-user}@${PROD_SSH_HOST:-host}', or re-run with --ask-pass."; }

    [[ -s "$tmp" ]] || { rm -f "$tmp"; die "The dump came back empty."; }

    # Only replace a known-good dump once the new one has arrived intact.
    mv "$tmp" "$DUMP_FILE"

    say "Wrote $DUMP_FILE ($(du -h "$DUMP_FILE" | cut -f1))"
}

# ----------------------------------------------------------------- the import

db_exec() {
    compose exec -T -e MYSQL_PWD="$DB_ROOT_PASSWORD" "$DB_SERVICE" "$@"
}

import_database() {
    require_vars DB_NAME DB_USER DB_ROOT_PASSWORD

    [[ -f "$DUMP_FILE" ]] || die "No dump at $DUMP_FILE - run without --skip-dump."

    running_db

    confirm "This DROPS the local database \"$DB_NAME\" and replaces it with the dump. Continue?" \
        || die "Aborted."

    say "Recreating $DB_NAME as $DB_CHARSET/$DB_COLLATE"

    db_exec mysql -u root -e "
        DROP DATABASE IF EXISTS \`$DB_NAME\`;
        CREATE DATABASE \`$DB_NAME\` CHARACTER SET $DB_CHARSET COLLATE $DB_COLLATE;
        GRANT SELECT, INSERT, UPDATE, DELETE ON \`$DB_NAME\`.* TO '$DB_USER'@'%';
        FLUSH PRIVILEGES;"

    say "Importing (MySQL 5.5 dump into MariaDB 10.5)"

    # sql_mode is cleared for the import only: the 5.5 schema carries a
    # zero-date default that MariaDB's stricter default mode can reject.
    gunzip -c "$DUMP_FILE" \
        | db_exec mysql -u root \
            --default-character-set="$DB_CHARSET" \
            --init-command="SET SESSION sql_mode=''" \
            "$DB_NAME"

    local tables
    tables=$(db_exec mysql -u root -N -B -e \
        "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_NAME';" \
        | tr -d '\r')

    say "Imported - $DB_NAME now holds $tables tables"

    [[ "$tables" -gt 0 ]] || warn "No tables were created; the dump may not have applied."
}

# ----------------------------------------------------------------- the assets

# Production's source/.gitignore is not wanted: locally the equivalent file is
# Storage/Resources/.gitignore, which is tracked and written by this repository.
TAR_EXCLUDES=(
    --exclude='.gitignore'
)

# Asks compose for the volume's real name rather than assuming the project
# prefix, falling back to the conventional form if that output ever changes.
resolve_volume() {
    local key="$1" name

    if [[ -n "$VOLUME_NAME" ]]; then
        printf '%s' "$VOLUME_NAME"
        return 0
    fi

    name=$(compose config --format json 2>/dev/null \
        | tr -d ' \n' \
        | sed -n "s/.*\"$key\":{\"name\":\"\([^\"]*\)\".*/\1/p" \
        | head -1)

    if [[ -z "$name" ]]; then
        name="${COMPOSE_PROJECT_NAME:-$(basename "$ROOT")}_$key"

        warn "Could not read the volume name from compose; assuming $name."
    fi

    printf '%s' "$name"
}

# Unpacks into the named volume with a throwaway container, so this works with
# the stack stopped - which is the situation on a first deploy.
seed_volume() {
    local volume
    volume="$(resolve_volume app_storage)"

    docker volume inspect "$volume" >/dev/null 2>&1 \
        || die "The volume $volume does not exist yet. Bring the stack up once
       ('docker compose up -d'), which creates it and seeds it from the image,
       then run this again."

    local existing
    existing=$(docker run --rm -v "$volume:/dest" "$SEED_IMAGE" \
        sh -c 'mkdir -p /dest/Resources; ls -A /dest/Resources 2>/dev/null | wc -l' | tr -d ' \r')

    say "Seeding $volume:/Resources (currently holds $existing entries)"

    # A volume freshly seeded from the image holds only Resources/.gitignore,
    # so anything beyond that means real content is already in there.
    if [[ "$existing" -gt 1 ]]; then
        confirm "$volume:/Resources already holds $existing entries. Unpack over them?" \
            || die "Aborted."
    fi

    # --strip-components=1 drops the archive's leading source/ directory,
    # because the volume IS that directory.
    #
    # No exclusions here: the git-tracked CSVs are working-tree concerns, and in
    # a volume the production copies are the ones wanted.
    if [[ -n "$LOCAL_ARCHIVE" ]]; then
        [[ -f "$LOCAL_ARCHIVE" ]] || die "No archive at $LOCAL_ARCHIVE"

        say "Unpacking $(du -h "$LOCAL_ARCHIVE" | cut -f1) from $LOCAL_ARCHIVE"

        docker run --rm -i -v "$volume:/dest" "$SEED_IMAGE" \
            tar -xzf - -C /dest/Resources --strip-components=1 < "$LOCAL_ARCHIVE"
    else
        require_vars PROD_ASSETS_PATH

        local parent base
        parent="$(dirname "$PROD_ASSETS_PATH")"
        base="$(basename "$PROD_ASSETS_PATH")"

        say "Streaming $base from $PROD_SSH_HOST straight into the volume"

        prod_ssh "tar -czf - -C $(printf '%q' "$parent") $(printf '%q' "$base")" \
            | docker run --rm -i -v "$volume:/dest" "$SEED_IMAGE" \
                tar -xzf - -C /dest --strip-components=1
    fi

    local count
    count=$(docker run --rm -v "$volume:/dest" "$SEED_IMAGE" \
        sh -c 'ls -A /dest/Resources | wc -l' | tr -d ' \r')

    say "$volume:/Resources now holds $count entries"
}

restore_assets() {
    if [[ $SEED_VOLUME -eq 1 ]]; then
        seed_volume
        return 0
    fi

    mkdir -p "$RESOURCES"

    if [[ -n "$LOCAL_ARCHIVE" ]]; then
        [[ -f "$LOCAL_ARCHIVE" ]] || die "No archive at $LOCAL_ARCHIVE"

        confirm "Unpack $(basename "$LOCAL_ARCHIVE") into the assets directory?" || die "Aborted."

        say "Unpacking $(du -h "$LOCAL_ARCHIVE" | cut -f1) from $LOCAL_ARCHIVE"

        tar -xzf "$LOCAL_ARCHIVE" -C "$RESOURCES" --strip-components=1 "${TAR_EXCLUDES[@]}"
    else
        require_vars PROD_ASSETS_PATH

        confirm "Fetch the assets from production and unpack them, overwriting what is there?" \
            || die "Aborted."

        local parent base
        parent="$(dirname "$PROD_ASSETS_PATH")"
        base="$(basename "$PROD_ASSETS_PATH")"

        say "Tarring $base on $PROD_SSH_HOST and unpacking it here"

        if [[ $KEEP_ARCHIVE -eq 1 ]]; then
            local archive="$DUMP_DIR/source.tar.gz"

            mkdir -p "$DUMP_DIR"

            # tee keeps a copy for the next run without a second transfer.
            prod_ssh "tar -czf - -C $(printf '%q' "$parent") $(printf '%q' "$base")" \
                | tee "$archive" \
                | tar -xzf - -C "$RESOURCES" --strip-components=1 "${TAR_EXCLUDES[@]}"

            say "Kept the archive at $archive ($(du -h "$archive" | cut -f1))"
        else
            prod_ssh "tar -czf - -C $(printf '%q' "$parent") $(printf '%q' "$base")" \
                | tar -xzf - -C "$RESOURCES" --strip-components=1 "${TAR_EXCLUDES[@]}"
        fi
    fi

    say "Resources now occupy $(du -sh "$RESOURCES" | cut -f1)"

    if [[ $DO_PUSH -eq 1 ]]; then
        push_assets
    else
        say "In development this path is bind mounted, so the container already"
        say "sees it. For production use --seed-volume, or --push to copy into"
        say "a running container built without the mount."
    fi
}

restore_fonts() {
    require_vars PROD_FONTS_PATH

    # Named explicitly rather than pulling the whole directory: the static
    # fonts there are tracked and come from the image.
    local members=""
    local f
    for f in "${FONT_FILES[@]}"; do
        members="$members $(printf '%q' "uploads/$f")"
    done

    if [[ $SEED_VOLUME -eq 1 ]]; then
        local volume
        volume="$(resolve_volume fonts_uploads)"

        docker volume inspect "$volume" >/dev/null 2>&1 \
            || die "The volume $volume does not exist yet. Bring the stack up once first."

        say "Streaming the uploaded fonts into volume $volume"

        # A font that has never been uploaded is simply absent upstream, so tar
        # is allowed to report it without failing the run.
        prod_ssh "cd $(printf '%q' "$PROD_FONTS_PATH") && tar -czf - $members 2>/dev/null || true" \
            | docker run --rm -i -v "$volume:/dest" "$SEED_IMAGE" \
                tar -xzf - -C /dest --strip-components=1 \
            || die "Could not fetch the fonts. If the error above is from ssh it is
       authentication or connectivity; otherwise check PROD_FONTS_PATH."

        local count
        count=$(docker run --rm -v "$volume:/dest" "$SEED_IMAGE" \
            sh -c 'ls -A /dest | wc -l' | tr -d ' \r')

        say "Volume $volume now holds $count entries"
    else
        mkdir -p "$FONTS_UPLOADS"

        say "Fetching the uploaded fonts from $PROD_SSH_HOST"

        prod_ssh "cd $(printf '%q' "$PROD_FONTS_PATH") && tar -czf - $members 2>/dev/null || true" \
            | tar -xzf - -C "$FONTS_UPLOADS" --strip-components=1 \
            || die "Could not fetch the fonts. If the error above is from ssh it is
       authentication or connectivity; otherwise check PROD_FONTS_PATH."

        for f in "${FONT_FILES[@]}"; do
            if [[ -f "$FONTS_UPLOADS/$f" ]]; then
                say "  $f ($(du -h "$FONTS_UPLOADS/$f" | cut -f1))"
            else
                warn "  $f is not present on production - nothing was uploaded there yet."
            fi
        done
    fi
}

push_assets() {
    local cid
    cid=$(compose ps -q php 2>/dev/null || true)

    if [[ -z "$cid" ]] || [[ "$(docker inspect -f '{{.State.Running}}' "$cid" 2>/dev/null)" != "true" ]]; then
        warn "The php service is not running - nothing to copy into."

        return 0
    fi

    say "Copying the resources into the php container"

    compose cp "$RESOURCES/." "php:$CONTAINER_RESOURCES/"
}

# ----------------------------------------------------------------------- main

collect_credentials

if [[ $DO_DB -eq 1 ]]; then
    if [[ $SKIP_DUMP -eq 1 ]]; then
        say "Reusing the existing dump at $DUMP_FILE"
    else
        dump_database
    fi

    import_database
fi

if [[ $DO_ASSETS -eq 1 ]]; then
    restore_assets
fi

if [[ $DO_FONTS -eq 1 ]]; then
    restore_fonts
fi

say "Done."
