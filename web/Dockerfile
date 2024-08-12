FROM nginx:1.27

RUN apt-get update && apt-get install -y nano procps git
COPY --chmod=0777 ./php/htdocs/webapp/www /usr/share/nginx/html
COPY ./web/nginx.conf /etc/nginx/conf.d/default.conf
WORKDIR /usr/share/nginx/html
ENTRYPOINT ["nginx", "-g", "daemon off;"]
