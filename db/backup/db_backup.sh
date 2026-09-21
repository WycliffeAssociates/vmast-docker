#!/bin/bash
#
# Physical backup of the whole MariaDB instance with mariadb-backup, run in a
# container that has the data directory mounted.
#
# The backup is prepared immediately, so what lands on disk is ready to restore
# with no further work, then compressed - 1.5GB of data files becomes about
# 110MB. Keeps the most recent BACKUP_KEEP sets.
#
# Rotation happens only after a new backup has been prepared and compressed, so
# a failing run can never reduce what you already have.
#
# For a portable, version-independent backup of just the application schema,
# use db_dump.sh instead; mariadb-backup output can only be restored into the
# same MariaDB major version it came from.
#
set -euo pipefail

DIR="${BACKUP_DIR:-/backup}"
KEEP="${BACKUP_KEEP:-7}"
DATADIR="${BACKUP_DATADIR:-/var/lib/mysql}"

DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"

STAMP=$(date +%Y-%m-%d_%H-%M-%S)
WORK="$DIR/.work-$STAMP"
TARGET="$DIR/mariabackup-${STAMP}.tar.gz"

say() { printf '%s  %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*"; }
die() { printf '%s  error: %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*" >&2; exit 1; }

cleanup() { rm -rf "$WORK"; }
trap cleanup EXIT INT TERM

mkdir -p "$DIR"

[[ -n "${MYSQL_ROOT_PASSWORD:-}" ]] || die "MYSQL_ROOT_PASSWORD is not set."
[[ -d "$DATADIR" ]] || die "No data directory at $DATADIR - is the dbdata volume mounted?"

# Keeps the password out of the process list.
export MYSQL_PWD="$MYSQL_ROOT_PASSWORD"

say "Backing up $DATADIR to $(basename "$TARGET")"

# --host is spelt out: mariadb-backup inherits -h from xtrabackup, where it
# means --datadir, so the short form silently does the wrong thing.
mariadb-backup --backup \
    --target-dir="$WORK" \
    --datadir="$DATADIR" \
    --host="$DB_HOST" \
    --port="$DB_PORT" \
    --user=root \
    2>&1 | tail -3 \
    || die "mariadb-backup --backup failed."

[[ -f "$WORK/xtrabackup_checkpoints" ]] \
    || die "The backup is missing xtrabackup_checkpoints - it did not complete."

say "Preparing the backup so it can be restored as-is"

mariadb-backup --prepare --target-dir="$WORK" 2>&1 | tail -2 \
    || die "mariadb-backup --prepare failed."

# --prepare rewrites this marker. MariaDB writes "log-applied"; Percona
# XtraBackup writes "full-prepared". Anything else means the prepare died part
# way and the set is not restorable.
grep -qE "backup_type = (log-applied|full-prepared)" "$WORK/xtrabackup_checkpoints" \
    || die "The backup was not prepared successfully. Nothing was rotated."

say "Compressing"

tar -czf "$TARGET.partial" -C "$WORK" . || { rm -f "$TARGET.partial"; die "Compression failed."; }
mv "$TARGET.partial" "$TARGET"

say "Wrote $(basename "$TARGET") ($(du -h "$TARGET" | cut -f1))"

# Sorted by name, not mtime: the timestamp in the filename is zero-padded so it
# sorts chronologically, and it survives a copy or an rsync without -t.
mapfile -t backups < <(ls -1 "$DIR"/mariabackup-*.tar.gz 2>/dev/null | sort -r || true)

if (( ${#backups[@]} > KEEP )); then
    for old in "${backups[@]:KEEP}"; do
        say "Removing old backup $(basename "$old")"
        rm -f "$old"
    done
fi

say "$(( ${#backups[@]} > KEEP ? KEEP : ${#backups[@]} )) backup(s) retained in $DIR"
