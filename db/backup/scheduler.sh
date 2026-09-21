#!/bin/bash
#
# Runs db_backup.sh once a day at BACKUP_HOUR, then sleeps until the next one.
#
# A loop rather than cron: the mariadb image has no cron daemon, and adding one
# to the db container would mean running a second process beside mysqld. This
# sleeps to the next occurrence of the hour rather than sleeping 24h from
# start-up, so the time stays put across restarts.
#
set -euo pipefail

HOUR="${BACKUP_HOUR:-2}"

say() { printf '%s  %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*"; }

say "Backup scheduler started; daily at ${HOUR}:00 UTC, keeping ${BACKUP_KEEP:-7}"

# Let the database finish starting before the first run.
until mariadb-admin ping -h "${DB_HOST:-db}" -u root --silent 2>/dev/null; do
    sleep 5
done

while true; do
    now=$(date +%s)
    next=$(date -d "today ${HOUR}:00" +%s 2>/dev/null)

    # Already past today's slot, so aim at tomorrow's.
    if (( next <= now )); then
        next=$(date -d "tomorrow ${HOUR}:00" +%s)
    fi

    say "Next backup at $(date -d "@$next" '+%Y-%m-%d %H:%M:%S')"
    sleep $(( next - now ))

    # A failed backup must not kill the scheduler - log it and wait for the
    # next slot.
    db_backup.sh || say "Backup failed; will try again at the next slot."
done
