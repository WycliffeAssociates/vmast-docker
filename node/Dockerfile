# FROM node:14-alpine
# -alpine has yarn installed already
FROM node:22-alpine3.20

# RUN apk update && apk add nano procps
RUN yarn global add pm2

USER node

WORKDIR /node
COPY ./package.json .
COPY ./yarn.lock .
# Dependencies are installed here, once, from yarn.lock. The container does
# not reinstall them at start, so it boots without reaching the npm registry.
RUN yarn install --frozen-lockfile
COPY . .

# pm2-runtime stays in the foreground as PID 1: it streams both processes'
# logs to stdout and stops them cleanly on `docker stop`.
CMD ["pm2-runtime", "ecosystem.config.js"]
