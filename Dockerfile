FROM nginx:1.27

# what's actually needed here? 
# https://mariadb.com/kb/en/mariadb-client/

RUN apt-get update && apt-get install -y nano procps git

# 0755: nginx only reads static assets out of this tree, it never writes to
# it and never executes the PHP. World-writable would be a free foothold.
COPY --chmod=0755 ./php/htdocs/webapp/www /usr/share/nginx/html
COPY ./ssl /etc/ssl
COPY ./web/nginx/localhost.conf /etc/nginx/conf.d/default.conf
# COPY ./web/nginx/ /etc/nginx/conf.d
# 
# COPY ./db/dump: /db_dump

# old
# WORKDIR /var/www/html
# new
WORKDIR /usr/share/nginx/html

# COPY ./start.sh /start.sh
# ENTRYPOINT ["/start.sh"]

ENTRYPOINT ["nginx", "-g", "daemon off;"]
