#!/bin/bash

if [ -z "$DEPLOY_ENV" ]; then
  echo "Error: Please set the 'DEPLOY_ENV' environment variable."
  exit 1
fi


if [ -z "$OP_SERVICE_ACCOUNT_TOKEN" ]; then
  echo "Error: Please set the 'OP_SERVICE_ACCOUNT_TOKEN' environment variable."
  exit 1
fi

shopt -s expand_aliases
# https://developer.1password.com/docs/cli/install-server/
# option 1
# ARCH="amd64"; \
#     OP_VERSION="v$(curl https://app-updates.agilebits.com/check/1/0/CLI2/en/2.0.0/N -s | grep -Eo '[0-9]+\.[0-9]+\.[0-9]+')"; \
#     curl -sSfo op.zip \
#     https://cache.agilebits.com/dist/1P/op2/pkg/"$OP_VERSION"/op_linux_"$ARCH"_"$OP_VERSION".zip \
#     && unzip -od /usr/local/bin/ op.zip \
#     && rm op.zip
OP_VERSION="v$(curl https://app-updates.agilebits.com/check/1/0/CLI2/en/2.0.0/N -s | grep -Eo '[0-9]+\.[0-9]+\.[0-9]+')"
curl -sSfLO "https://cache.agilebits.com/dist/1P/op2/pkg/${OP_VERSION}/op_linux_${ARCH}_${OP_VERSION}.zip"
unzip -od ./opcli op.zip
rm op.zip

# Set the connect server vars
export OP_SERVICE_ACCOUNT_TOKEN=$OP_SERVICE_ACCOUNT_TOKEN
export OP_CONNECT_HOST=$OP_CONNECT_HOST


# in case anything was hanging around
docker compose down; 

# Launch!
./opcli/op run --env-file="./.env.deploy" -- docker compose up -d; 

rm -rf ./opcli



