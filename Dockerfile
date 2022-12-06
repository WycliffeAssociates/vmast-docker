FROM nginx:1.21

RUN apt-get update && apt-get install -y nano procps git

WORKDIR /var/www/html

COPY ./start.sh /start.sh
ENTRYPOINT ["/start.sh"]
