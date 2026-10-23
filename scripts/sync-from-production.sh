#!/usr/bin/env bash
#
# Copies the production database and the user-created assets into a running
# vmast stack: a developer's local stack or a deployed one (dev, prod).
#
#   Database: mysqldump runs on the production host and the gzip is streamed
#             back, so only SSH has to be reachable. Production is MySQL 5.5
#             and the container is MariaDB 10.5, so this is a logical dump -
#             a physical xtrabackup set cannot cross that gap.
#
#   Assets:   the source directory is tarred on the production host and piped
#             straight into the stack, so no 100MB+ archive is parked on either
#             side unless --keep-archive asks for one.
#
# The stack is found by its compose project, and everything is written through
# its running containers: the database through the db container (with that
# container's own credentials), the assets to wherever the php container keeps
# them - a named volume when deployed, the working tree in development. So it
# needs no .env and no compose files, and runs the same everywhere.
#
# Configure it with scripts/.env.sync (see .env.sync.example), then, with the
# stack running:
#
#   ./scripts/sync-from-production.sh                 # database, assets, fonts
#   ./scripts/sync-from-production.sh --db-only
#   ./scripts/sync-from-production.sh --assets-only --archive ~/Desktop/vmast_backups/source.tar.gz
#
# On a deploy host the Jenkins job ships this script into the deploy directory,
# and it is run from there (the copy is not executable):
#
#   bash scripts/sync-from-production.sh --ask-pass
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

DUMP_DIR="$ROOT/db/dump"
DUMP_FILE="$DUMP_DIR/production.sql.gz"

# Where the php container keeps user content. Production still keeps the
# resources under Assets/source; here they live in the framework's storage
# directory, so the archive's leading source/ is stripped rather than preserved.
CT_STORAGE="/usr/share/nginx/html/app/Storage"
CT_RESOURCES="$CT_STORAGE/Resources"
CT_FONTS="/usr/share/nginx/html/app/Templates/Default/Assets/fonts/uploads"

# Only these two are user content; everything else in fonts/ ships in the image.
FONT_FILES=(SUN.woff BackSUN.woff)

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
KEEP_ARCHIVE=0
KEEP_DUMP=0
LOCAL_ARCHIVE=""
SKIP_DUMP=0
ASSUME_YES=0
ASK_PASS=0
PROJECT="${COMPOSE_PROJECT_NAME:-}"

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
  --no-db              Skip the database
  --no-assets          Skip the assets
  --no-fonts           Skip the fonts
  --project NAME       Compose project of the target stack. Defaults to
                       COMPOSE_PROJECT_NAME, else the name compose gives this
                       directory - right when run from the repo or deploy dir.
  --skip-dump          Import the dump kept at db/dump/production.sql.gz by an
                       earlier --keep-dump run instead of fetching a new one
  --keep-dump          Keep the dump after importing it (it holds production's
                       user data, so it is deleted by default)
  --archive PATH       Unpack this local source.tar.gz instead of fetching
                       it from production
  --keep-archive       Also save the fetched assets as a .tar.gz next to the dump
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
        --no-db)        DO_DB=0 ;;
        --no-assets)    DO_ASSETS=0 ;;
        --no-fonts)     DO_FONTS=0 ;;
        --project)      PROJECT="${2:?--project needs a name}"; shift ;;
        --skip-dump)    SKIP_DUMP=1 ;;
        --keep-dump)    KEEP_DUMP=1 ;;
        --archive)      LOCAL_ARCHIVE="${2:?--archive needs a path}"; shift ;;
        --keep-archive) KEEP_ARCHIVE=1 ;;
        --ask-pass)     ASK_PASS=1 ;;
        # Earlier versions chose between the working tree, a running container
        # and the named volumes with these; the target is now read from the
        # running php container, so they are accepted and ignored.
        --push|--seed-volume|--prod)
                        warn "$1 is no longer needed and is ignored." ;;
        -y|--yes)       ASSUME_YES=1 ;;
        -h|--help)      usage ;;
        *)              die "Unknown option: $1 (try --help)" ;;
    esac
    shift
done

[[ $DO_DB -eq 1 || $DO_ASSETS -eq 1 || $DO_FONTS -eq 1 ]] || die "Nothing to do."

# ---------------------------------------------------------------- configuration

[[ -f "$ROOT/scripts/.env.sync" ]] \
    || die "Missing scripts/.env.sync - copy scripts/.env.sync.example and fill it in."

# shellcheck disable=SC1091
set -a; source "$ROOT/scripts/.env.sync"; set +a

PROD_SSH_PORT="${PROD_SSH_PORT:-22}"
PROD_DB_HOST="${PROD_DB_HOST:-127.0.0.1}"
PROD_DB_PORT="${PROD_DB_PORT:-3306}"

# One server can host several vmast sites. PROD_APP_ROOT names the site's app/
# directory, from which both asset paths follow; either can still be set alone.
if [[ -n "${PROD_APP_ROOT:-}" ]]; then
    PROD_ASSETS_PATH="${PROD_ASSETS_PATH:-$PROD_APP_ROOT/Templates/Default/Assets/source}"
    PROD_FONTS_PATH="${PROD_FONTS_PATH:-$PROD_APP_ROOT/Templates/Default/Assets/fonts}"
fi

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
}

# A dump fetched by this run holds production's user data, so unless --keep-dump
# asked for it, it goes on any exit - including an abort at a confirmation, an
# error or an interrupt - as does the runtime directory.
FRESH_DUMP=0

cleanup() {
    [[ -n "$RUNTIME_DIR" ]] && rm -rf "$RUNTIME_DIR"

    if [[ $FRESH_DUMP -eq 1 && $KEEP_DUMP -eq 0 ]]; then
        rm -f "$DUMP_FILE" "$DUMP_FILE.partial"
    fi
}

trap cleanup EXIT
trap 'exit 130' INT TERM

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

    FRESH_DUMP=1

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

    check_dump "$tmp" || { rm -f "$tmp"; exit 1; }

    # Only replace a known-good dump once the new one has arrived intact.
    mv "$tmp" "$DUMP_FILE"

    say "Wrote $DUMP_FILE ($(du -h "$DUMP_FILE" | cut -f1))"
}

# ------------------------------------------------------------------ the stack

# The containers are looked up by the labels compose puts on them, so neither
# the compose files nor their variables are needed - on a deploy host the
# variables only ever existed in the deploy's environment.
default_project() {
    # compose's own rule for naming a project after its directory.
    basename "$ROOT" | tr '[:upper:]' '[:lower:]' | tr -cd 'a-z0-9_-'
}

service_container() {
    docker ps -q \
        --filter "label=com.docker.compose.project=$PROJECT" \
        --filter "label=com.docker.compose.service=$1" | head -1
}

DB_CID=""
PHP_CID=""

find_stack() {
    [[ -n "$PROJECT" ]] || PROJECT="$(default_project)"

    local svc cid
    for svc in db php; do
        [[ $svc == db && $DO_DB -eq 0 ]] && continue
        [[ $svc == php && $DO_ASSETS -eq 0 && $DO_FONTS -eq 0 ]] && continue

        cid="$(service_container "$svc")"

        if [[ -z "$cid" ]]; then
            local running
            running=$(docker ps --filter label=com.docker.compose.service=db \
                --format '{{.Label "com.docker.compose.project"}}' | sort -u | tr '\n' ' ')

            die "No running '$svc' container in compose project '$PROJECT'.
       Start the stack first (deploy it, or 'make local' in development). If it
       is running under another project name, pass --project NAME.
       Projects with a running db: ${running:-none}"
        fi

        [[ $svc == db ]] && DB_CID="$cid" || PHP_CID="$cid"
    done

    say "Target: compose project '$PROJECT'"
}

# Runs a command in the db container as root, with the root password taken
# from that container's own environment - it never passes through here.
db_root() {
    docker exec -i "$DB_CID" sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" exec "$@"' sh "$@"
}

db_env() {
    docker exec "$DB_CID" printenv "$1" 2>/dev/null | tr -d '\r'
}

# "type source" of the php container's mount at a path: "volume <name>" when
# deployed, "bind <host dir>" in development.
php_mount() {
    docker inspect -f \
        "{{range .Mounts}}{{if eq .Destination \"$1\"}}{{.Type}} {{.Source}}{{end}}{{end}}" \
        "$PHP_CID"
}

# Unpacks the tar.gz on stdin into a directory of the php container, which must
# lie on the mount at $2. A bind mount is written on the host directly, so files
# stay owned by whoever runs this; a volume is written through the container as
# www-data, the user that serves and writes them.
unpack_into() {
    local dest="$1" mountpoint="$2" type source
    shift 2

    read -r type source <<< "$(php_mount "$mountpoint")"

    case "$type" in
        bind)
            local dir="$source${dest#"$mountpoint"}"
            mkdir -p "$dir"
            tar -xzf - -C "$dir" "$@"
            ;;
        volume)
            docker exec -i -u www-data "$PHP_CID" sh -c \
                'd="$1"; shift; mkdir -p "$d" && exec tar -xzf - -C "$d" "$@"' sh "$dest" "$@"
            ;;
        *)
            die "$mountpoint in the php container is on neither a volume nor a bind
       mount, so anything unpacked there would vanish when it is recreated."
            ;;
    esac
}

count_entries() {
    docker exec "$PHP_CID" sh -c 'ls -A "$1" 2>/dev/null | wc -l' sh "$1" | tr -d ' \r'
}

# ------------------------------------------------------------------ preflight

# One read-only pass over production before anything local is touched, so a
# wrong path or a missing tool stops the run while the database is still intact.
preflight() {
    local check_db=0 assets="" fonts=""

    [[ $DO_DB -eq 1 && $SKIP_DUMP -eq 0 ]] && check_db=1
    [[ $DO_ASSETS -eq 1 && -z "$LOCAL_ARCHIVE" ]] && { require_vars PROD_ASSETS_PATH; assets="$PROD_ASSETS_PATH"; }
    [[ $DO_FONTS -eq 1 ]] && { require_vars PROD_FONTS_PATH; fonts="$PROD_FONTS_PATH"; }

    [[ $check_db -eq 1 || -n "$assets" || -n "$fonts" ]] || return 0

    say "Checking $PROD_SSH_HOST"

    local rc=0
    prod_ssh "sh -s -- $(printf '%q ' "$check_db" "${PROD_APP_ROOT:-}" "${PROD_DB_NAME:-}" \
        "$assets" "$fonts" "${FONT_FILES[@]}")" <<'PREFLIGHT' || rc=$?
check_db=$1 app_root=$2 db_name=$3 assets=$4 fonts=$5
shift 5
status=0

if [ "$check_db" = 1 ]; then
    if command -v mysqldump >/dev/null 2>&1; then
        echo "  ok    mysqldump at $(command -v mysqldump)"
    else
        echo "  FAIL  mysqldump is not installed"; status=1
    fi

    # Cross-check that the site the assets come from uses the database being
    # dumped; a server hosting several sites makes them easy to mismatch.
    if [ -n "$app_root" ]; then
        conf=$(grep -shE "^[[:space:]]*DB_NAME[[:space:]]*=|define\([\"']DB_NAME" \
            "$app_root/.env" "$app_root/Config.php" 2>/dev/null)
        if [ -z "$conf" ]; then
            echo "  info  no DB_NAME setting under $app_root to cross-check"
        elif printf '%s\n' "$conf" | grep -qw -- "$db_name"; then
            echo "  ok    the site at $app_root uses database $db_name"
        else
            echo "  WARN  the site at $app_root does not appear to use database $db_name:"
            printf '%s\n' "$conf" | sed 's/^/          /'
            [ "$status" = 0 ] && status=3
        fi
    fi
fi

if [ -n "$assets" ]; then
    if [ -d "$assets" ]; then
        echo "  ok    assets at $assets ($(du -sh "$assets" 2>/dev/null | cut -f1))"
    else
        echo "  FAIL  no assets directory at $assets"; status=1
    fi
fi

if [ -n "$fonts" ]; then
    if [ -d "$fonts" ]; then
        found=""
        for f in "$@"; do
            if [ -f "$fonts/uploads/$f" ] || [ -f "$fonts/$f" ]; then found="$found $f"; fi
        done
        echo "  ok    fonts at $fonts (uploaded:${found:- none})"
    else
        echo "  FAIL  no fonts directory at $fonts"; status=1
    fi
fi

exit $status
PREFLIGHT

    case "$rc" in
        0) ;;
        3) confirm "Continue anyway?" || die "Aborted." ;;
        *) die "Production is not ready for this run; see above. Nothing has been changed." ;;
    esac
}

# ----------------------------------------------------------------- the import

# Checks a dump before anything is dropped for it. A fresh dump is checked on
# arrival and discarded if bad; a kept one (--skip-dump) again before import.
check_dump() {
    gzip -t "$1" 2>/dev/null \
        || { warn "The dump is not a complete gzip file - the transfer was cut off."; return 1; }

    # mysqldump ends a dump it finished with this comment. Without it the
    # server stopped part way, and importing would leave a partial database.
    gunzip -c "$1" | tail -n 1 | grep -q 'Dump completed' \
        || { warn "The dump is incomplete - mysqldump stopped before the end."; return 1; }
}

import_database() {
    [[ -f "$DUMP_FILE" ]] \
        || die "No dump at $DUMP_FILE - --skip-dump needs one kept by an earlier --keep-dump run."

    check_dump "$DUMP_FILE" || die "Not importing $DUMP_FILE; nothing has been changed."

    local db_name db_user
    db_name="$(db_env MYSQL_DATABASE)"
    db_user="$(db_env MYSQL_USER)"

    [[ -n "$db_name" && -n "$db_user" ]] \
        || die "The db container has no MYSQL_DATABASE/MYSQL_USER in its environment."

    confirm "This DROPS database \"$db_name\" in project \"$PROJECT\" and replaces it with the dump. Continue?" \
        || die "Aborted."

    say "Recreating $db_name as $DB_CHARSET/$DB_COLLATE"

    db_root mysql -u root -e "
        DROP DATABASE IF EXISTS \`$db_name\`;
        CREATE DATABASE \`$db_name\` CHARACTER SET $DB_CHARSET COLLATE $DB_COLLATE;
        GRANT SELECT, INSERT, UPDATE, DELETE ON \`$db_name\`.* TO '$db_user'@'%';
        FLUSH PRIVILEGES;"

    say "Importing (MySQL 5.5 dump into MariaDB 10.5)"

    # sql_mode is cleared for the import only: the 5.5 schema carries a
    # zero-date default that MariaDB's stricter default mode can reject.
    gunzip -c "$DUMP_FILE" \
        | db_root mysql -u root \
            --default-character-set="$DB_CHARSET" \
            --init-command="SET SESSION sql_mode=''" \
            "$db_name"

    local tables
    tables=$(db_root mysql -u root -N -B -e \
        "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$db_name';" \
        | tr -d '\r')

    say "Imported - $db_name now holds $tables tables"

    [[ "$tables" -gt 0 ]] || warn "No tables were created; the dump may not have applied."

    if [[ $FRESH_DUMP -eq 1 && $KEEP_DUMP -eq 0 ]]; then
        rm -f "$DUMP_FILE"
        say "Deleted the dump (it holds production's user data; --keep-dump keeps it)"
    fi
}

# ----------------------------------------------------------------- the assets

# Production's source/.gitignore is not wanted: in development the equivalent
# file is Storage/Resources/.gitignore, which is tracked by this repository.
TAR_EXCLUDES=(
    --exclude='.gitignore'
)

restore_assets() {
    local existing
    existing="$(count_entries "$CT_RESOURCES")"

    # A fresh stack holds at most Resources/.gitignore, so anything beyond that
    # means real content is already there.
    if [[ "$existing" -gt 1 ]]; then
        confirm "Resources already holds $existing entries. Unpack production's over them?" \
            || die "Aborted."
    fi

    if [[ -n "$LOCAL_ARCHIVE" ]]; then
        [[ -f "$LOCAL_ARCHIVE" ]] || die "No archive at $LOCAL_ARCHIVE"

        say "Unpacking $(du -h "$LOCAL_ARCHIVE" | cut -f1) from $LOCAL_ARCHIVE"

        unpack_into "$CT_RESOURCES" "$CT_STORAGE" --strip-components=1 "${TAR_EXCLUDES[@]}" \
            < "$LOCAL_ARCHIVE"
    else
        local parent base fetch
        parent="$(dirname "$PROD_ASSETS_PATH")"
        base="$(basename "$PROD_ASSETS_PATH")"
        fetch="tar -czf - -C $(printf '%q' "$parent") $(printf '%q' "$base")"

        say "Streaming $base from $PROD_SSH_HOST into the stack"

        if [[ $KEEP_ARCHIVE -eq 1 ]]; then
            local archive="$DUMP_DIR/source.tar.gz"

            mkdir -p "$DUMP_DIR"

            # tee keeps a copy for the next run without a second transfer.
            prod_ssh "$fetch" | tee "$archive" \
                | unpack_into "$CT_RESOURCES" "$CT_STORAGE" --strip-components=1 "${TAR_EXCLUDES[@]}"

            say "Kept the archive at $archive ($(du -h "$archive" | cut -f1))"
        else
            prod_ssh "$fetch" \
                | unpack_into "$CT_RESOURCES" "$CT_STORAGE" --strip-components=1 "${TAR_EXCLUDES[@]}"
        fi
    fi

    say "Resources now holds $(count_entries "$CT_RESOURCES") entries"
}

restore_fonts() {
    # Named explicitly rather than pulling the whole directory: the static
    # fonts there are tracked and come from the image. Older deployments keep
    # the uploads directly in fonts/, newer ones in fonts/uploads/, so each file
    # is taken from wherever it exists and archived without a directory. A
    # font that was never uploaded is simply left out; with none at all an
    # empty archive is sent, so the unpacking side still gets valid input.
    local names
    names="$(printf '%q ' "${FONT_FILES[@]}")"

    local fetch_fonts
    fetch_fonts="cd $(printf '%q' "$PROD_FONTS_PATH") || exit 1
        set --
        for f in $names; do
            if [ -f \"uploads/\$f\" ]; then set -- \"\$@\" -C \"\$PWD/uploads\" \"\$f\"
            elif [ -f \"\$f\" ]; then set -- \"\$@\" -C \"\$PWD\" \"\$f\"
            fi
        done
        if [ \$# -gt 0 ]; then tar -czf - \"\$@\"; else tar -czf - -T /dev/null; fi"

    say "Fetching the uploaded fonts from $PROD_SSH_HOST"

    prod_ssh "$fetch_fonts" | unpack_into "$CT_FONTS" "$CT_FONTS" \
        || die "Could not fetch the fonts. If the error above is from ssh it is
       authentication or connectivity; otherwise check PROD_FONTS_PATH."

    local f
    for f in "${FONT_FILES[@]}"; do
        if docker exec "$PHP_CID" test -f "$CT_FONTS/$f"; then
            say "  $f"
        else
            warn "  $f is not present on production - nothing was uploaded there yet."
        fi
    done
}

# ----------------------------------------------------------------------- main

# The stack is found first, so nothing is typed for a run that cannot proceed.
find_stack
collect_credentials
preflight

if [[ $DO_DB -eq 1 ]]; then
    if [[ $SKIP_DUMP -eq 1 ]]; then
        say "Reusing the dump at $DUMP_FILE"
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
