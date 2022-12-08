FROM nginx:1.21

RUN apt-get update && apt-get install -y nano procps git mariadb-client-10.5

WORKDIR /var/www/html

COPY ./start.sh /start.sh
ENTRYPOINT ["/start.sh"]
