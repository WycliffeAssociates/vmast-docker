#!/bin/bash
#
# Production deploy: pull the CI-built images and start the stack with secrets
# resolved from 1password. Intended for Jenkins, but runs anywhere that has
# docker (with the compose plugin) and the 1password CLI `op` on PATH.
#
# It keeps Maxim's secret model from run.sh exactly: op:// references in
# .env.deploy are resolved by `op run` into the environment for the duration of
# the deploy only, never written to disk, and compose interpolates them into the
# per-service `environment:` blocks. The only difference from run.sh is that
# images are PULLED (docker-compose.prod.yml) instead of built on the host.
#
# Required environment:
#   DEPLOY_ENV                selects the op section, e.g. production / staging
#   OP_SERVICE_ACCOUNT_TOKEN  1password service-account token
#   IMAGE_TAG                 a tag the docker-build workflow published
#                             (commit SHA, branch slug, or latest)
#
# The deploy host must already be logged in to the registry (docker login) if
# the vmast images are private; Jenkins does that with its registry credential.

set -euo pipefail

: "${DEPLOY_ENV:?Set DEPLOY_ENV (selects the op section, e.g. production)}"
: "${OP_SERVICE_ACCOUNT_TOKEN:?Set OP_SERVICE_ACCOUNT_TOKEN (1password service-account token)}"
: "${IMAGE_TAG:?Set IMAGE_TAG (a Docker Hub tag CI published, e.g. a commit SHA, branch slug, or latest)}"

command -v op >/dev/null 2>&1 || {
  echo "Error: the 1password CLI 'op' is not on PATH. Install it on the deploy host/agent."
  exit 1
}

export DEPLOY_ENV OP_SERVICE_ACCOUNT_TOKEN IMAGE_TAG

COMPOSE="docker compose -f docker-compose.yml -f docker-compose.prod.yml"

# Resolve secrets once and run both pull and up inside that environment.
op run --env-file="./.env.deploy" -- sh -c "${COMPOSE} pull && ${COMPOSE} up -d"
