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
RUN apt-get update && apt-get install -y lsb-release wget curl nano mariadb-client-10.5 

# todo: remove
COPY ./dump/ /docker-entrypoint-initdb.d
# COPY ./entrypoint.sh /
# RUN chmod +x /entrypoint.sh
# Let maria handle the entrypooint
# ENTRYPOINT ["/entrypoint.sh"]

# WORKDIR /backup
