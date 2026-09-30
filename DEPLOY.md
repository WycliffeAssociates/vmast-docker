# Deploying vmast-docker

There are two deploy paths. They share one secret model (1password via `op`,
resolved at deploy time, never written to disk) and one compose base file. They
differ only in where the images come from.

## Secrets model (both paths)

`.env.deploy` holds `op://` references, not values. At deploy time `op run`
resolves them into the environment for the length of the `docker compose up`,
and compose interpolates them into each service's `environment:` block. The
production `docker-compose.yml` declares no `env_file:`, so no plaintext secrets
file is ever required or written on the host. Both paths need:

- `DEPLOY_ENV` - the 1password section to read (e.g. `production`, `staging`).
- `OP_SERVICE_ACCOUNT_TOKEN` - a 1password service-account token.

## Path 1: build on the host (Maxim's `run.sh`, unchanged)

Manual / single-host deploy that builds the images from the checked-out source:

```sh
export DEPLOY_ENV=production
export OP_SERVICE_ACCOUNT_TOKEN=...
./run.sh
```

`run.sh` runs `op run --env-file=.env.deploy -- docker compose up -d` against the
base file only, so images are built on the host.

## Path 2: pull CI-built images (Jenkins, `deploy.sh`)

CI (`.github/workflows/docker-build.yml`) builds and pushes
`wycliffeassociates/vmast-{web,php,db,node}` to Docker Hub, tagged by commit SHA,
branch slug, and `latest`. The production overlay `docker-compose.prod.yml`
points the services at those images, and `deploy.sh` pulls and starts them:

```sh
export DEPLOY_ENV=production
export OP_SERVICE_ACCOUNT_TOKEN=...
export IMAGE_TAG=latest          # or a commit SHA / branch slug CI published
./deploy.sh
```

`deploy.sh` runs, with secrets resolved by `op run`:

```sh
docker compose -f docker-compose.yml -f docker-compose.prod.yml pull
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

Requirements on the deploy host/agent: docker with the compose plugin, the
`op` CLI on PATH, and `docker login` to Docker Hub if the images are private.

### Why a separate overlay, not `docker-compose.override.yml`

`docker-compose.override.yml` is auto-loaded by every bare `docker compose`
command, which would silently change `run.sh` and `make local`. Naming it
`docker-compose.prod.yml` and passing it explicitly with `-f` keeps those
workflows untouched; only the pull-based deploy opts in.

### Jenkins

`Jenkinsfile` runs Path 2 on an agent that has docker + `op`. It expects:

- agent label `docker-deploy`
- credential `vmast-op-service-account` (Secret text) -> `OP_SERVICE_ACCOUNT_TOKEN`
- credential `dockerhub` (Username/password) for the registry login

Parameters `DEPLOY_ENV` and `IMAGE_TAG` choose the section and the tag to deploy.

If Jenkins deploys to a **remote** docker host rather than the agent itself,
keep `deploy.sh` as-is and change the Deploy stage to run it over ssh on that
host (the repo, `op`, and docker must be present there), e.g.:

```groovy
sshagent(['vmast-deploy-host']) {
  sh '''
    ssh deploy@vmast-host "cd /opt/vmast-docker && git pull && \
      DEPLOY_ENV='${DEPLOY_ENV}' IMAGE_TAG='${IMAGE_TAG}' \
      OP_SERVICE_ACCOUNT_TOKEN='${OP_SERVICE_ACCOUNT_TOKEN}' ./deploy.sh"
  '''
}
```

### Image tags

CI (`.github/workflows/docker-build.yml`) tags every pushed image with the
commit SHA, the branch slug, and `latest`, and additionally with an
**environment name** when the pushed branch is a deploy branch. That env tag is
what makes `IMAGE_TAG=$DEPLOY_ENV` line up: deploying `production` runs the
image tagged `production`.

The branch -> environment mapping lives in the workflow's "Set docker tags"
step:

```sh
case "$CI_REF_NAME" in
  main)  ENV_TAG=prod ;;
  *)     ENV_TAG="$CI_REF_NAME_SLUG" ;;
esac
```

`main` publishes `wycliffeassociates/vmast-*:prod`, and every other branch
publishes a tag equal to its own slugified name. So `IMAGE_TAG=prod ./deploy.sh`
deploys `main`, and `IMAGE_TAG=dev ./deploy.sh` deploys the `dev` branch. The
slug keeps the tag valid even for branch names containing `/`. Every push also
gets sha / branch-slug / `latest` tags, so you can always deploy a specific
commit.
