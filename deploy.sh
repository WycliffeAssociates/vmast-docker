#!/bin/bash
#
# Production deploy, modeled on WACS/deploy.sh. Runs ON the docker host: Jenkins
# copies this script plus the compose files and .env.deploy to the host (Publish
# Over SSH) and runs it there, so docker and op run locally on that host.
#
# op runs as the 1password/op:2 container - nothing is installed on the host.
# Each op:// reference in .env.deploy is read straight into this process's
# environment; no secret is ever written to a file. compose then pulls the
# CI-built images (docker-compose.prod.yml) and starts the stack.
#
# Required env:
#   DEPLOY_ENV                op section + env image tag (e.g. dev, prod)
#   OP_SERVICE_ACCOUNT_TOKEN  1password service-account token
# Optional:
#   IMAGE_TAG                 image tag to deploy (default: $DEPLOY_ENV)
#
# Everything else, including the published host ports (WEB_PORT, NODE_PORT),
# comes from the 1Password section via .env.deploy.

set -euo pipefail

: "${DEPLOY_ENV:?Set DEPLOY_ENV (op section + image tag, e.g. dev)}"
: "${OP_SERVICE_ACCOUNT_TOKEN:?Set OP_SERVICE_ACCOUNT_TOKEN (1password service-account token)}"
export OP_SERVICE_ACCOUNT_TOKEN
export IMAGE_TAG="${IMAGE_TAG:-$DEPLOY_ENV}"

# Containerized op - no op binary installed on the host. Needs outbound HTTPS to
# 1password.
op() { docker run --rm -e OP_SERVICE_ACCOUNT_TOKEN 1password/op:2 op "$@"; }

# Resolve every op:// reference in .env.deploy into this process's environment.
# .env.deploy stays the single source of truth, shared with run.sh; only
# $DEPLOY_ENV is expanded in the reference path.
while IFS='=' read -r key ref; do
  case "$key" in ''|'#'*|DEPLOY_ENV) continue ;; esac
  ref=${ref//\$DEPLOY_ENV/$DEPLOY_ENV}
  value=$(op read "$ref") || { echo "Error: could not read $ref" >&2; exit 1; }
  export "$key=$value"
done < .env.deploy

COMPOSE="docker compose -f docker-compose.yml -f docker-compose.prod.yml"

# Pull the CI-built images for this tag. set -e makes a missing tag fail the
# deploy here, so compose never falls back to building from contexts that were
# not shipped to the host. Then recreate only what changed.
$COMPOSE pull
$COMPOSE up -d --remove-orphans

unset OP_SERVICE_ACCOUNT_TOKEN
