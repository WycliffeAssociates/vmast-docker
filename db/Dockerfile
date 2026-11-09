# Backup tooling for the db-backup service (and scripts/db-restore.sh). The
# database itself runs the official mariadb image unchanged (see
# docker-compose.yml); this image is that same image plus the backup scripts,
# and must stay on the same version: a physical backup can only be restored
# into the engine and major version it was taken from.
FROM mariadb:10.5.25

# mariabackup (MariaDB's fork of Percona XtraBackup, which cannot back up
# MariaDB 10.3+), mariadb-dump and gpg for the encrypted backups all ship in the
# base image. Fail the build, not the nightly backup, if that ever changes.
RUN for tool in mariabackup mbstream mariadb mariadb-dump gpg gzip tar; do \
        command -v "$tool" >/dev/null || { echo "missing: $tool" >&2; exit 1; }; \
    done

# The backup scripts live on PATH, not in /backup: that path is a volume, and
# a named volume starts empty, so anything placed there in the image would be
# invisible the moment it is mounted.
COPY --chmod=0755 ./backup/*.sh /usr/local/bin/
