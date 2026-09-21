#!/bin/bash
#
# Restores the application database from a logical dump written by db_dump.sh.
#
# Unlike the physical restore this needs no downtime: the dump is SQL, so it
# goes into a running server. Only the application schema is replaced - the
# mysql schema, and therefore user accounts and grants, are left alone.
#
#   db_dump_restore.sh --list
#   db_dump_restore.sh                                  # newest dump
#   db_dump_restore.sh vmast-2026-09-21_03-00-00.sql.gz
#
set -euo pipefail

DIR="${BACKUP_DIR:-/backup}"

DB_NAME="${DB_NAME:?DB_NAME is not set}"
DB_USER="${DB_USER:-}"
DB_HOST="${DB_HOST:-db}"

DUMP=""
ASSUME_YES=0

say() { printf '\033[1;34m==>\033[0m %s\n' "$*"; }
die() { printf '\033[1;31merror:\033[0m %s\n' "$*" >&2; exit 1; }

list_dumps() {
    ls -1 "$DIR"/${DB_NAME}-*.sql.gz "$DIR"/${DB_NAME}-*.sql.gz.gpg 2>/dev/null | sort -r || true
}

while [[ $# -gt 0 ]]; do
    case "$1" in
        --list)
            mapfile -t found < <(list_dumps)
            [[ ${#found[@]} -gt 0 ]] || die "No dumps in $DIR"
            for d in "${found[@]}"; do
                printf '  %-44s %s\n' "$(basename "$d")" "$(du -h "$d" | cut -f1)"
            done
            exit 0
            ;;
        -y|--yes) ASSUME_YES=1 ;;
        -*)       die "Unknown option: $1" ;;
        *)        DUMP="$1" ;;
    esac
    shift
done

[[ -n "${MYSQL_ROOT_PASSWORD:-}" ]] || die "MYSQL_ROOT_PASSWORD is not set."
export MYSQL_PWD="$MYSQL_ROOT_PASSWORD"

if [[ -z "$DUMP" ]]; then
    DUMP=$(list_dumps | head -1)
    [[ -n "$DUMP" ]] || die "No dumps in $DIR - run db_dump.sh first."
    say "Using the newest dump: $(basename "$DUMP")"
fi

[[ -f "$DUMP" ]] || DUMP="$DIR/$DUMP"
[[ -f "$DUMP" ]] || die "No such dump: $DUMP"

if [[ $ASSUME_YES -eq 0 ]]; then
    printf 'This DROPS the database "%s" and replaces it with %s.\n' "$DB_NAME" "$(basename "$DUMP")"
    read -r -p 'Type the database name to continue: ' reply
    [[ "$reply" == "$DB_NAME" ]] || die "Aborted."
fi

# Decrypted into the pipe rather than onto disk, so no plaintext copy is left.
if [[ "$DUMP" == *.gpg ]]; then
    [[ -n "${BACKUP_PASSPHRASE:-}" ]] \
        || die "$(basename "$DUMP") is encrypted but BACKUP_PASSPHRASE is not set."

    reader() { gpg --batch --quiet --passphrase-fd 3 --decrypt "$DUMP" 3<<<"$BACKUP_PASSPHRASE" | gunzip -c; }
else
    reader() { gunzip -c "$DUMP"; }
fi

say "Recreating $DB_NAME"

# utf8/utf8_unicode_ci matches what the tables declare; the dump recreates the
# tables but not the database itself.
mariadb -h "$DB_HOST" -u root -e "
    DROP DATABASE IF EXISTS \`$DB_NAME\`;
    CREATE DATABASE \`$DB_NAME\` CHARACTER SET utf8 COLLATE utf8_unicode_ci;"

if [[ -n "$DB_USER" ]]; then
    mariadb -h "$DB_HOST" -u root -e "
        GRANT SELECT, INSERT, UPDATE, DELETE ON \`$DB_NAME\`.* TO '$DB_USER'@'%';
        FLUSH PRIVILEGES;"
fi

say "Importing $(basename "$DUMP") (this takes a few minutes)"

# sql_mode is cleared for the import only, in case the dump carries anything
# the server's stricter default would reject.
reader | mariadb -h "$DB_HOST" -u root \
    --default-character-set=utf8 \
    --init-command="SET SESSION sql_mode=''" \
    "$DB_NAME"

tables=$(mariadb -h "$DB_HOST" -u root -N -B -e \
    "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_NAME';")

say "Restored - $DB_NAME holds $tables tables. No restart needed."
