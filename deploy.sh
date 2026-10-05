#!/bin/bash
#
# Production deploy, modeled on WACS/deploy.sh.
#
# Secrets are resolved from 1password through the containerized op CLI
# (1password/op:2 - nothing is installed on the node) straight into this
# process's environment, and docker compose interpolates them into each
# service's `environment:` block. No secret is ever written to a file or copied
# to another host.
#
# Where the containers run:
#   - DOCKER_HOST unset  -> everything runs locally (deploy.sh is run on the
#                           docker host itself, like Maxim's run.sh).
#   - DOCKER_HOST set     -> e.g. ssh://deploy@vmast-prod. op and secret
#     (recommended for      resolution stay HERE (op is pinned to the local
#      Jenkins)             docker), and compose ships the resolved values to
#                           that remote daemon as container config over the
#                           Docker API. The box running the containers never
#                           receives the 1password token or any env file.
#
# Required env:
#   DEPLOY_ENV                op section + env image tag (e.g. prod)
#   OP_SERVICE_ACCOUNT_TOKEN  1password service-account token
# Optional:
#   IMAGE_TAG                 image tag to deploy (default: $DEPLOY_ENV)
#   DOCKER_HOST               remote docker daemon to target (default: local)

set -euo pipefail

: "${DEPLOY_ENV:?Set DEPLOY_ENV (op section + image tag, e.g. prod)}"
: "${OP_SERVICE_ACCOUNT_TOKEN:?Set OP_SERVICE_ACCOUNT_TOKEN (1password service-account token)}"
export OP_SERVICE_ACCOUNT_TOKEN
export IMAGE_TAG="${IMAGE_TAG:-$DEPLOY_ENV}"

# Containerized op, pinned to the LOCAL docker (DOCKER_HOST="") so secret
# resolution never runs on the remote deploy host even when DOCKER_HOST points
# there. Needs outbound HTTPS to 1password from wherever this runs.
op() { DOCKER_HOST="" docker run --rm -e OP_SERVICE_ACCOUNT_TOKEN 1password/op:2 op "$@"; }

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

# Pull the images CI published for this env, then recreate only what changed.
$COMPOSE pull
$COMPOSE up -d --remove-orphans

unset OP_SERVICE_ACCOUNT_TOKEN
