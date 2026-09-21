# FROM mysql:5.7-debian
FROM mariadb:10.5.25
# lsb_release -- print distribution specific information
# Get debian info
# 


# RUN apt-key adv --keyserver keyserver.ubuntu.com --recv-keys B7B3B788A8D3785C
# RUN apt-get update
# RUN apt-get update && apt-get install -y lsb-release wget curl nano mariadb-client-10.5 && \
#  	cd /tmp && wget https://repo.percona.com/apt/percona-release_latest.$(lsb_release -sc)_all.deb && \
# 	dpkg -i percona-release_latest.$(lsb_release -sc)_all.deb && \
# 	apt-get update && apt-get install -y percona-xtrabackup-24 qpress
# Client is probably only needed for dev though?  i
# mariabackup - MariaDB's fork of xtrabackup, and what db_backup.sh and
# db_restore.sh drive - already ships in this image at /usr/bin/mariabackup,
# so only gnupg has to be added for the encrypted backups.
#
# On a MySQL 5.7 image instead, the equivalent is Percona's xtrabackup; see the
# commented block above. A physical backup can only be restored into the same
# engine and major version it was taken from.
RUN apt-get update && apt-get install -y \
	lsb-release wget curl nano gnupg mariadb-client-10.5 \
	&& rm -rf /var/lib/apt/lists/*

# The backup scripts live on PATH, not in /backup: that path is a volume, and
# a named volume starts empty, so anything placed there in the image would be
# invisible the moment it is mounted.
COPY --chmod=0755 ./backup/*.sh /usr/local/bin/

# todo: remove
COPY ./dump/ /docker-entrypoint-initdb.d
# COPY ./entrypoint.sh /
# RUN chmod +x /entrypoint.sh
# Let maria handle the entrypooint
# ENTRYPOINT ["/entrypoint.sh"]

# WORKDIR /backup
