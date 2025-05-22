#!/bin/sh

/scripts/hosts.sh

yarn install

pm2 start server.js --restart-delay=1000 && \
pm2 start mailer.js --restart-delay=1000

pm2 logs
