FROM mariadb:10.5.25

RUN apt-get update && apt-get install -y lsb-release wget curl nano mariadb-client-10.5 

COPY ./dump/ /docker-entrypoint-initdb.d
