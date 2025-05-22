FROM node:22-alpine3.20

RUN yarn global add pm2

WORKDIR /node
COPY ./package.json .
COPY ./yarn.lock .
RUN yarn install
COPY ./node_entrypoint.sh /node_entrypoint.sh
RUN chmod +x /node_entrypoint.sh
COPY . .
ENTRYPOINT [ "/node_entrypoint.sh" ]
