#!/bin/bash
#
# Hourly physical backup with mariadb-backup: a full base once a day, an
# incremental every other hour.
#
#   /backup/current/          the set being built, kept raw
#     base/                   full backup
#     inc1/ .. inc23/         hourly increments
#     .stamp .counter         set identity and position
#   /backup/set-<stamp>.tar.gz   completed sets, compressed
#
# The active set stays uncompressed and UNPREPARED because both are required
# to extend it: --prepare is destructive, and --incremental-basedir reads the
# previous directory's files. Preparation happens at restore time instead.
#
# A set is sealed - compressed and replaced by a tarball - when the next base
# is due, which keeps only one 1.4GB set on disk at a time.
#
set -euo pipefail

DIR="${BACKUP_DIR:-/backup}"
KEEP="${BACKUP_KEEP:-7}"
DATADIR="${BACKUP_DATADIR:-/var/lib/mysql}"
BASE_HOUR="${BACKUP_HOUR:-2}"

DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"

CURRENT="$DIR/current"

say() { printf '%s  %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*"; }
die() { printf '%s  error: %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*" >&2; exit 1; }

mkdir -p "$DIR"

[[ -n "${MYSQL_ROOT_PASSWORD:-}" ]] || die "MYSQL_ROOT_PASSWORD is not set."
[[ -d "$DATADIR" ]] || die "No data directory at $DATADIR - is the dbdata volume mounted?"

export MYSQL_PWD="$MYSQL_ROOT_PASSWORD"

# --host spelt out: mariadb-backup inherits -h from xtrabackup, where it means
# --datadir, so the short form silently does the wrong thing.
backup_to() {
    local target="$1"; shift

    mariadb-backup --backup \
        --target-dir="$target" \
        --datadir="$DATADIR" \
        --host="$DB_HOST" \
        --port="$DB_PORT" \
        --user=root \
        "$@" 2>&1 | tail -2

    [[ -f "$target/xtrabackup_checkpoints" ]] \
        || { rm -rf "$target"; die "Backup to $(basename "$target") did not complete."; }
}

seal_current() {
    local stamp
    stamp=$(cat "$CURRENT/.stamp")

    local archive="$DIR/set-${stamp}.tar.gz"

    say "Sealing the previous set as $(basename "$archive")"

    tar -czf "$archive.partial" -C "$CURRENT" . \
        || { rm -f "$archive.partial"; die "Could not compress the finished set."; }

    mv "$archive.partial" "$archive"
    rm -rf "$CURRENT"

    say "Sealed $(basename "$archive") ($(du -h "$archive" | cut -f1))"
}

rotate() {
    # By name: the stamp is zero-padded so it sorts chronologically, and unlike
    # mtime it survives a copy or an rsync without -t.
    mapfile -t sets < <(ls -1 "$DIR"/set-*.tar.gz 2>/dev/null | sort -r || true)

    if (( ${#sets[@]} > KEEP )); then
        for old in "${sets[@]:KEEP}"; do
            say "Removing old set $(basename "$old")"
            rm -f "$old"
        done
    fi

    say "$(( ${#sets[@]} > KEEP ? KEEP : ${#sets[@]} )) sealed set(s) plus the active one"
}

start_new_set() {
    local stamp
    stamp=$(date +%Y-%m-%d_%H-%M-%S)

    say "Starting a new set with a full base"

    mkdir -p "$CURRENT"
    backup_to "$CURRENT/base"

    printf '%s' "$stamp" > "$CURRENT/.stamp"
    printf '0' > "$CURRENT/.counter"

    say "Base written for set $stamp ($(du -sh "$CURRENT/base" | cut -f1))"
}

add_increment() {
    local counter previous
    counter=$(( $(cat "$CURRENT/.counter") + 1 ))

    if (( counter == 1 )); then
        previous="$CURRENT/base"
    else
        previous="$CURRENT/inc$(( counter - 1 ))"
    fi

    [[ -d "$previous" ]] || die "The set is missing $(basename "$previous"); cannot extend it."

    say "Adding increment $counter on top of $(basename "$previous")"

    backup_to "$CURRENT/inc$counter" --incremental-basedir="$previous"

    # An increment taken while InnoDB has not checkpointed since the previous
    # backup spans no LSN range. mariadb-backup still writes an .ibd.meta for
    # every tablespace, and --prepare reads meta-without-delta as "this
    # tablespace is gone" and DELETES it - applying such an increment empties
    # the database. Discard it instead of keeping a destructive increment.
    local deltas
    deltas=$(find "$CURRENT/inc$counter" -name '*.delta' | wc -l | tr -d ' ')

    if (( deltas == 0 )); then
        say "Increment $counter contains no changed pages; discarding it."
        say "Nothing has changed on disk since the previous backup."

        rm -rf "$CURRENT/inc$counter"

        return 0
    fi

    printf '%s' "$counter" > "$CURRENT/.counter"

    say "Increment $counter written, $deltas changed tablespace(s) ($(du -sh "$CURRENT/inc$counter" | cut -f1))"
}

# A new base is due when there is no set at all, or when the clock reaches the
# hour reserved for it.
if [[ ! -d "$CURRENT" ]]; then
    start_new_set
elif [[ "$(date +%-H)" == "$BASE_HOUR" ]]; then
    seal_current
    start_new_set
else
    add_increment
fi

rotate
