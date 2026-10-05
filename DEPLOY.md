# Deploying vmast-docker

Two deploy paths. They share the same secret model (1password, resolved at
deploy time, never written to disk) and the same compose base file. They differ
only in where the images come from.

## Secrets model (both paths)

`.env.deploy` holds `op://` references, not values, and is the single source of
truth for which secrets the stack needs. Secrets are resolved from 1password
through the **containerized** op CLI (`1password/op:2` - nothing is installed on
the host) straight into the deploy process's environment, and docker compose
interpolates them into each service's `environment:` block. The production
`docker-compose.yml` declares no `env_file:`, so no plaintext secrets file is
ever required or written. Both paths need:

- `DEPLOY_ENV` - the 1password section to read and the env image tag (e.g. `prod`).
- `OP_SERVICE_ACCOUNT_TOKEN` - a 1password service-account token.

## Path 1: build on the host (Maxim's `run.sh`, unchanged)

Manual / single-host deploy that builds the images from the checked-out source,
run on the docker host itself:

```sh
export DEPLOY_ENV=prod
export OP_SERVICE_ACCOUNT_TOKEN=...
./run.sh
```

## Path 2: pull CI-built images (Jenkins, `deploy.sh`)

CI (`.github/workflows/docker-build.yml`) builds and pushes
`wycliffeassociates/vmast-{web,php,db,node}` to Docker Hub. The production
overlay `docker-compose.prod.yml` points the services at those images, and
`deploy.sh` resolves secrets and runs the stack:

```sh
export DEPLOY_ENV=prod
export OP_SERVICE_ACCOUNT_TOKEN=...
# optional:
# export IMAGE_TAG=prod                 # default: $DEPLOY_ENV
# export DOCKER_HOST=ssh://deploy@prod  # default: local docker
./deploy.sh
```

`deploy.sh` (see the file for the exact steps):

1. defines `op` as `docker run --rm 1password/op:2 op` - containerized, so the
   node needs docker but **not** an installed op binary;
2. reads each `op://` ref from `.env.deploy` into its own environment via
   `op read` - no file is written;
3. runs `docker compose -f docker-compose.yml -f docker-compose.prod.yml pull`
   then `up -d`.

### Deploying to a separate host (the important part)

Set `DOCKER_HOST=ssh://user@prod-host`. `op` is pinned to the **local** docker,
so secret resolution stays on the Jenkins agent; `docker compose` then ships the
resolved values to the remote daemon as container configuration over the Docker
API. The host that actually runs the containers never receives the 1password
token and never has a secrets file written to it - it only gets the resolved
environment as part of each container's config. That is the whole reason this
path does not materialize an env file and copy it over.

If the Jenkins agent is itself the docker host, leave `DOCKER_HOST` unset and it
all runs locally.

Requirements on the agent: docker (with the compose plugin). Outbound HTTPS to
1password. For a remote `DOCKER_HOST`, an SSH key to the prod host (its host key
in `known_hosts`) and docker on that host. The vmast images must be pullable by
the target daemon (the compose client forwards registry auth, so a
`docker login` on the agent covers private images; they are public by default).

### Jenkins

`Jenkinsfile` runs Path 2. It uses:

- agent label `docker` - a node that can run containers (for the op container
  and the compose client);
- credential `vmast-op-service-account` (Secret text) -> `OP_SERVICE_ACCOUNT_TOKEN`;
- credential `vmast-prod-ssh` (SSH private key) -> used by `ssh://` docker;
- params `DEPLOY_ENV`, `IMAGE_TAG` (blank = `DEPLOY_ENV`), and
  `DEPLOY_DOCKER_HOST` (the `ssh://` target; blank = deploy on the agent).

Nothing is installed on the agent and nothing is copied to the prod host.

## Image tags

CI tags every pushed image with the commit SHA, the branch slug, and `latest`,
and additionally with an environment name so `IMAGE_TAG=$DEPLOY_ENV` lines up.
The branch -> environment mapping lives in the workflow's "Set docker tags" step:

```sh
case "$CI_REF_NAME" in
  main)  ENV_TAG=prod ;;
  *)     ENV_TAG="$CI_REF_NAME_SLUG" ;;
esac
```

`main` publishes `wycliffeassociates/vmast-*:prod`, every other branch publishes
its slugified name. So `DEPLOY_ENV=prod` deploys `main`'s images. You can always
deploy a specific commit with `IMAGE_TAG=<sha>`.
