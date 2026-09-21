#!/bin/bash
#
# Restores a mariadb-backup set into the data directory. Runs inside a
# container that has both the backup directory and the data directory mounted,
# with the database server STOPPED - mariadb-backup --copy-back requires an
# empty datadir and refuses to run against a live server.
#
# This is the inner half of the restore. Use scripts/db-restore.sh on the host,
# which stops the db service, invokes this, and starts it again.
#
#   db_restore.sh --list
#   db_restore.sh                                  # newest set
#   db_restore.sh mariabackup-2026-09-21_02-00-00.tar.gz
#
set -euo pipefail

DIR="${BACKUP_DIR:-/backup}"
DATADIR="${BACKUP_DATADIR:-/var/lib/mysql}"

BACKUP=""
ASSUME_YES=0

say() { printf '\033[1;34m==>\033[0m %s\n' "$*"; }
die() { printf '\033[1;31merror:\033[0m %s\n' "$*" >&2; exit 1; }

list_backups() { ls -1 "$DIR"/mariabackup-*.tar.gz 2>/dev/null | sort -r || true; }

while [[ $# -gt 0 ]]; do
    case "$1" in
        --list)
            mapfile -t found < <(list_backups)
            [[ ${#found[@]} -gt 0 ]] || die "No backups in $DIR"
            for b in "${found[@]}"; do
                printf '  %-44s %s\n' "$(basename "$b")" "$(du -h "$b" | cut -f1)"
            done
            exit 0
            ;;
        -y|--yes) ASSUME_YES=1 ;;
        -*)       die "Unknown option: $1" ;;
        *)        BACKUP="$1" ;;
    esac
    shift
done

WORK="$DIR/.restore-$$"
cleanup() { rm -rf "$WORK"; }
trap cleanup EXIT INT TERM

if [[ -z "$BACKUP" ]]; then
    BACKUP=$(list_backups | head -1)
    [[ -n "$BACKUP" ]] || die "No backups in $DIR - run db_backup.sh first."
    say "Using the newest backup: $(basename "$BACKUP")"
fi

[[ -f "$BACKUP" ]] || BACKUP="$DIR/$BACKUP"
[[ -f "$BACKUP" ]] || die "No such backup: $BACKUP"

[[ -d "$DATADIR" ]] || die "No data directory at $DATADIR - is the dbdata volume mounted?"

# If the server is up, its socket or a running mysqld will be visible here.
# copy-back into a live datadir corrupts it, so refuse rather than risk it.
if [[ -S "$DATADIR/mysqld.sock" ]] || pgrep -x mariadbd >/dev/null 2>&1 || pgrep -x mysqld >/dev/null 2>&1; then
    die "The database server appears to be running. Stop it first - use scripts/db-restore.sh on the host."
fi

if [[ $ASSUME_YES -eq 0 ]]; then
    printf 'This ERASES %s and replaces it with %s.\n' "$DATADIR" "$(basename "$BACKUP")"
    read -r -p 'Type ERASE to continue: ' reply
    [[ "$reply" == "ERASE" ]] || die "Aborted."
fi

mkdir -p "$WORK"

say "Unpacking $(basename "$BACKUP")"

tar -xzf "$BACKUP" -C "$WORK" || die "Could not unpack the backup."

grep -qE "backup_type = (log-applied|full-prepared)" "$WORK/xtrabackup_checkpoints" 2>/dev/null \
    || die "This set is not prepared - refusing to restore it."

say "Emptying $DATADIR"

# Dotfiles included; --copy-back refuses to run unless the directory is empty.
find "$DATADIR" -mindepth 1 -delete

say "Copying the backup into place"

mariadb-backup --copy-back --target-dir="$WORK" --datadir="$DATADIR" 2>&1 | tail -3 \
    || die "mariadb-backup --copy-back failed. The data directory is now incomplete - restore again before starting the server."

chown -R mysql:mysql "$DATADIR"

say "Restored. Start the db service again to bring the server up."
