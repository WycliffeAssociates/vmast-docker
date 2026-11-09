#!/bin/bash
#
# Logical backup of the application database, run inside the db container.
#
# mariadb-dump rather than mariabackup: a physical backup is tied to the server
# version, has to be --prepare'd, and can only be restored over a stopped
# server's data directory. A dump of this database is around 70MB gzipped and
# restores into a running server with a pipe.
#
# Keeps the most recent BACKUP_KEEP backups and deletes the rest. Rotation only
# happens after a new backup has been written and verified, so a failing run
# can never leave you with fewer than you started with.
#
set -euo pipefail

DIR="${BACKUP_DIR:-/backup}"
KEEP="${BACKUP_KEEP:-7}"

DB_NAME="${DB_NAME:?DB_NAME is not set}"
DB_HOST="${DB_HOST:-db}"

STAMP=$(date +%Y-%m-%d_%H-%M-%S)
TARGET="$DIR/${DB_NAME}-${STAMP}.sql.gz"

say() { printf '%s  %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*"; }
die() { printf '%s  error: %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*" >&2; exit 1; }

mkdir -p "$DIR"

[[ -n "${MYSQL_ROOT_PASSWORD:-}" ]] || die "MYSQL_ROOT_PASSWORD is not set."

# Keeps the password out of the process list.
export MYSQL_PWD="$MYSQL_ROOT_PASSWORD"

say "Backing up $DB_NAME to $(basename "$TARGET")"

# --single-transaction: a consistent snapshot of the InnoDB tables without
# locking them, so the application keeps running during the dump.
mariadb-dump \
    -h "$DB_HOST" -u root \
    --single-transaction \
    --quick \
    --routines \
    --triggers \
    --events \
    --default-character-set=utf8mb4 \
    "$DB_NAME" \
    | gzip -c > "$TARGET.partial" \
    || { rm -f "$TARGET.partial"; die "mariadb-dump failed."; }

# A dump that was cut short still gzips cleanly, so check for the trailer
# mariadb-dump writes only once it has finished.
if ! gunzip -c "$TARGET.partial" | tail -5 | grep -q "Dump completed"; then
    rm -f "$TARGET.partial"
    die "The dump is incomplete - no completion marker. Nothing was rotated."
fi

if [[ -n "${BACKUP_PASSPHRASE:-}" ]]; then
    gpg --batch --yes --symmetric --cipher-algo aes256 \
        --passphrase-fd 0 -o "$TARGET.gpg" "$TARGET.partial" <<<"$BACKUP_PASSPHRASE" \
        || { rm -f "$TARGET.partial" "$TARGET.gpg"; die "Encryption failed."; }

    rm -f "$TARGET.partial"
    TARGET="$TARGET.gpg"
else
    mv "$TARGET.partial" "$TARGET"
fi

say "Wrote $(basename "$TARGET") ($(du -h "$TARGET" | cut -f1))"

# Rotation: newest first, everything past KEEP goes. Runs only now that a good
# backup is on disk.
#
# Sorted by name rather than mtime: the timestamp in the filename is
# zero-padded, so it sorts chronologically, and unlike mtime it survives a
# copy, an rsync without -t, or a restore from backup media.
mapfile -t backups < <(ls -1 "$DIR"/${DB_NAME}-*.sql.gz "$DIR"/${DB_NAME}-*.sql.gz.gpg 2>/dev/null | sort -r || true)

if (( ${#backups[@]} > KEEP )); then
    for old in "${backups[@]:KEEP}"; do
        say "Removing old backup $(basename "$old")"
        rm -f "$old"
    done
fi

say "$(( ${#backups[@]} > KEEP ? KEEP : ${#backups[@]} )) backup(s) retained in $DIR"
