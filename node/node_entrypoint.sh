#!/bin/sh

/scripts/hosts.sh

#export NODE_EXTRA_CA_CERTS=$SSL_CA && \
pm2 start server.js --restart-delay=1000 && \
pm2 start mailer.js --restart-delay=1000

pm2 logs
