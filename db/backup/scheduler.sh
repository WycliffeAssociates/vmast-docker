#!/bin/bash
#
# Runs db_backup.sh every hour. db_backup.sh decides what that means: a full
# base at BACKUP_HOUR, an incremental otherwise.
#
# A loop rather than cron: the mariadb image has no cron daemon, and adding one
# to the db container would mean running a second process beside mysqld.
#
set -euo pipefail

BASE_HOUR="${BACKUP_HOUR:-2}"
DUMP_HOUR="${DUMP_HOUR:-3}"

say() { printf '%s  %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*"; }

say "Backup scheduler started; keeping ${BACKUP_KEEP:-7} of each"
say "  hourly  physical backup, full base at ${BASE_HOUR}:00 UTC"
say "  daily   logical dump at ${DUMP_HOUR}:00 UTC"

# Let the database finish starting before the first run.
until mariadb-admin ping -h "${DB_HOST:-db}" -u root --silent 2>/dev/null; do
    sleep 5
done

while true; do
    now=$(date +%s)

    # Top of the next hour, so runs stay on the clock across restarts rather
    # than drifting an hour from whenever the container started.
    next=$(( now - now % 3600 + 3600 ))

    say "Next run at $(date -d "@$next" '+%Y-%m-%d %H:%M:%S')"
    sleep $(( next - now ))

    # A failed run must not kill the scheduler - log it and wait for the next
    # hour.
    db_backup.sh || say "Physical backup failed; will try again next hour."

    # The logical dump runs once a day alongside the physical backups. It is
    # the only copy that survives a MariaDB major version change, since a
    # mariadb-backup set can only be restored into the version it came from.
    # Its failure is independent of the physical one, so it is reported
    # separately rather than skipped.
    if [[ "$(date +%-H)" == "$DUMP_HOUR" ]]; then
        db_dump.sh || say "Logical dump failed; will try again tomorrow."
    fi
done
