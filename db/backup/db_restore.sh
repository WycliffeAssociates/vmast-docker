#!/bin/bash
#
# Restores a mariadb-backup set to a chosen hour. Runs inside a container that
# has the backup directory and the data directory mounted, with the database
# server STOPPED.
#
# The set is assembled in a working copy - prepare is destructive, so the
# backup itself is never touched - then copied into the data directory.
#
#   db_restore.sh --list
#   db_restore.sh                        # active set, latest increment
#   db_restore.sh --hour 5               # active set, up to increment 5
#   db_restore.sh --set set-2026-09-20_02-00-00.tar.gz --hour 0
#
# --hour 0 restores the base alone; --hour N applies inc1 through incN.
#
set -euo pipefail

DIR="${BACKUP_DIR:-/backup}"
DATADIR="${BACKUP_DATADIR:-/var/lib/mysql}"
CURRENT="$DIR/current"

SET=""
HOUR=""
ASSUME_YES=0

say() { printf '\033[1;34m==>\033[0m %s\n' "$*"; }
die() { printf '\033[1;31merror:\033[0m %s\n' "$*" >&2; exit 1; }

sealed_sets() { ls -1 "$DIR"/set-*.tar.gz 2>/dev/null | sort -r || true; }

do_list() {
    if [[ -d "$CURRENT" ]]; then
        printf '  %-40s active, base + %s increment(s), %s\n' \
            "current ($(cat "$CURRENT/.stamp" 2>/dev/null || echo 'no stamp'))" \
            "$(cat "$CURRENT/.counter" 2>/dev/null || echo 0)" \
            "$(du -sh "$CURRENT" | cut -f1)"
    fi

    local s
    for s in $(sealed_sets); do
        printf '  %-40s sealed, %s\n' "$(basename "$s")" "$(du -h "$s" | cut -f1)"
    done

    [[ -d "$CURRENT" ]] || [[ -n "$(sealed_sets)" ]] || die "No backups in $DIR"
}

while [[ $# -gt 0 ]]; do
    case "$1" in
        --list)   do_list; exit 0 ;;
        --set)    SET="${2:?--set needs a name}"; shift ;;
        --hour)   HOUR="${2:?--hour needs a number}"; shift ;;
        -y|--yes) ASSUME_YES=1 ;;
        -*)       die "Unknown option: $1" ;;
        *)        SET="$1" ;;
    esac
    shift
done

WORK="$DIR/.restore-$$"
cleanup() { rm -rf "$WORK"; }
trap cleanup EXIT INT TERM

[[ -d "$DATADIR" ]] || die "No data directory at $DATADIR - is the dbdata volume mounted?"

# copy-back into a live datadir corrupts it, so refuse rather than risk it.
if [[ -S "$DATADIR/mysqld.sock" ]] || pgrep -x mariadbd >/dev/null 2>&1 || pgrep -x mysqld >/dev/null 2>&1; then
    die "The database server appears to be running. Stop it first - use scripts/db-restore.sh on the host."
fi

mkdir -p "$WORK"

# Assemble the chosen set in $WORK/set. A sealed set is extracted there, which
# doubles as the working copy; the active set has to be copied so that
# preparing it does not destroy the only base that can still take increments.
if [[ -z "$SET" || "$SET" == "current" ]]; then
    [[ -d "$CURRENT" ]] || die "There is no active set. Choose a sealed one with --set."

    available=$(cat "$CURRENT/.counter")

    say "Using the active set ($(cat "$CURRENT/.stamp")), $available increment(s) available"

    # The whole set is copied, increments included, not just the base.
    # --prepare --incremental-dir CONSUMES the increment it applies: the .delta
    # files are removed as they are merged. Applying them straight out of
    # /backup/current would leave the active set unrestorable ever again.
    mkdir -p "$WORK/set"
    cp -r "$CURRENT/base" "$WORK/set/base"

    for (( c = 1; c <= available; c++ )); do
        [[ -d "$CURRENT/inc$c" ]] && cp -r "$CURRENT/inc$c" "$WORK/set/inc$c"
    done

    SOURCE_INC="$WORK/set"
else
    [[ -f "$SET" ]] || SET="$DIR/$SET"
    [[ -f "$SET" ]] || die "No such set: $SET"

    say "Extracting $(basename "$SET")"

    mkdir -p "$WORK/set"
    tar -xzf "$SET" -C "$WORK/set" || die "Could not unpack the set."

    available=$(cat "$WORK/set/.counter" 2>/dev/null || echo 0)
    SOURCE_INC="$WORK/set"
fi

[[ -d "$WORK/set/base" ]] || die "The set has no base directory."

# Default to everything the set holds.
[[ -n "$HOUR" ]] || HOUR="$available"

[[ "$HOUR" =~ ^[0-9]+$ ]] || die "--hour must be a number."
(( HOUR <= available )) || die "This set only has $available increment(s); asked for $HOUR."

if [[ $ASSUME_YES -eq 0 ]]; then
    printf 'This ERASES %s and restores the set up to increment %s.\n' "$DATADIR" "$HOUR"
    read -r -p 'Type ERASE to continue: ' reply
    [[ "$reply" == "ERASE" ]] || die "Aborted."
fi

say "Preparing the base"

mariadb-backup --prepare --target-dir="$WORK/set/base" 2>&1 | tail -1 \
    || die "Preparing the base failed."

for (( i = 1; i <= HOUR; i++ )); do
    [[ -d "$SOURCE_INC/inc$i" ]] || die "Increment $i is missing from the set."

    # An increment holding .ibd.meta files but no .delta is not empty, it is
    # destructive: --prepare reads a meta without its delta as a dropped
    # tablespace and removes it, which empties every table in the set.
    deltas=$(find "$SOURCE_INC/inc$i" -name '*.delta' | wc -l | tr -d ' ')

    (( deltas > 0 )) || die "Increment $i carries no changed pages, only tablespace
       metadata. Applying it would delete every tablespace in the backup.
       Restore with --hour $(( i - 1 )) instead."

    say "Applying increment $i ($deltas changed tablespace(s))"

    mariadb-backup --prepare --target-dir="$WORK/set/base" --incremental-dir="$SOURCE_INC/inc$i" 2>&1 | tail -1 \
        || die "Applying increment $i failed."
done

grep -qE "backup_type = (log-applied|full-prepared)" "$WORK/set/base/xtrabackup_checkpoints" \
    || die "The set did not prepare cleanly - refusing to restore it."

say "Emptying $DATADIR"

# Dotfiles included; --copy-back refuses to run unless the directory is empty.
find "$DATADIR" -mindepth 1 -delete

say "Copying into place"

mariadb-backup --copy-back --target-dir="$WORK/set/base" --datadir="$DATADIR" 2>&1 | tail -1 \
    || die "copy-back failed. The data directory is now incomplete - restore again before starting the server."

chown -R mysql:mysql "$DATADIR"

say "Restored to increment $HOUR. Start the db service to bring the server up."
