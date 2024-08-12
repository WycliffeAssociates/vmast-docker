# FROM node:14-alpine
# -alpine has yarn installed already
FROM node:21-alpine3.19

# RUN apk update && apk add nano procps
RUN yarn global add pm2

WORKDIR /node
COPY ./package.json .
COPY ./yarn.lock .
RUN yarn install
COPY ./node_entrypoint.sh /node_entrypoint.sh
RUN chmod +x /node_entrypoint.sh
COPY . .
ENTRYPOINT [ "/node_entrypoint.sh" ]
