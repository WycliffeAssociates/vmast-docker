# Deploying vmast-docker

Two deploy paths. They share the same secret model (1password, resolved on the
host at deploy time, never written to disk) and the same compose base file. They
differ only in where the images come from.

## Secrets model (both paths)

`.env.deploy` holds `op://` references, not values, and is the single source of
truth for which secrets the stack needs. On the host, `deploy.sh` resolves each
reference from 1password through the **containerized** op CLI (`1password/op:2` -
nothing is installed on the host) straight into the deploy process's
environment, and docker compose interpolates them into each service's
`environment:` block. The production `docker-compose.yml` declares no
`env_file:`, so no plaintext secrets file is ever required or written. Both
paths need:

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

## Path 2: pull CI-built images (Jenkins -> host, `deploy.sh`)

This mirrors the `WACS_deploy_dev` Jenkins job. CI
(`.github/workflows/docker-build.yml`) builds and pushes
`wycliffeassociates/vmast-{web,php,db,node}` to Docker Hub. The production
overlay `docker-compose.prod.yml` points the services at those images, and
`deploy.sh` (run on the host) resolves secrets and starts the stack:

1. Jenkins checks out this repo on the `docker` node and binds the 1password
   token to `OP_SERVICE_ACCOUNT_TOKEN`.
2. Publish Over SSH copies `docker-compose.yml`, `docker-compose.prod.yml`,
   `deploy.sh` and `.env.deploy` to the deploy host, then runs on that host:

   ```sh
   cd <deploy dir>
   export DEPLOY_ENV=dev
   export IMAGE_TAG=dev            # or a specific tag; blank -> DEPLOY_ENV
   export OP_SERVICE_ACCOUNT_TOKEN=${OP_SERVICE_ACCOUNT_TOKEN}
   source deploy.sh
   ```
3. `deploy.sh` runs op as the `1password/op:2` container, reads each `op://` ref
   from `.env.deploy` into its environment, then
   `docker compose -f docker-compose.yml -f docker-compose.prod.yml pull` and
   `up -d`.

Only the compose files, `deploy.sh` and `.env.deploy` are copied to the host;
the build contexts are not, because the images are pulled, not built. No secret
is ever written to a file or copied. The host needs docker (with the compose
plugin) and outbound HTTPS to 1password and Docker Hub.

### Jenkins: pipeline

`Jenkinsfile` implements Path 2 with the `sshPublisher` step. It uses:

- agent label `docker`;
- a Publish Over SSH "SSH Server" named by `SSH_SERVER` (default `<ssh-server-name>`);
- `REMOTE_DIR` (default `<deploy dir>`) as the deploy dir on that host;
- credential `vmast-op-service-account` (Secret text) -> `OP_SERVICE_ACCOUNT_TOKEN`;
- params `DEPLOY_ENV` and `IMAGE_TAG` (blank = `DEPLOY_ENV`).

### Jenkins: freestyle (matching WACS_deploy_dev)

If you configure a freestyle job (as WACS does), clone `WACS_deploy_dev` and
change:

- **Git**: `git@github.com:WycliffeAssociates/vmast-docker.git`, the deploy
  branch, same SSH credential.
- **Restrict to node**: `docker`.
- **Build Environment -> secret text binding**: your vmast 1password-token
  credential -> variable `OP_SERVICE_ACCOUNT_TOKEN`.
- **Send files or execute commands over SSH** (Publish Over SSH):
  - SSH Server: the vmast deploy host (configured in Jenkins).
  - Source files: `docker-compose.yml,docker-compose.prod.yml,deploy.sh,.env.deploy`
  - Remote directory: e.g. `<deploy dir>`
  - Exec command:

    ```sh
    #!/bin/bash
    cd <deploy dir>
    export IMAGE_TAG=dev
    export DEPLOY_ENV=dev
    export OP_SERVICE_ACCOUNT_TOKEN=${OP_SERVICE_ACCOUNT_TOKEN}
    source deploy.sh
    ```

The only differences from the WACS job are the repo, the added
`docker-compose.prod.yml` in source files, and dropping the WACS-gitea-specific
`READER_BASE_LINK` / `LINTER_BASE_LINK` / `GREEKROOM_BASE_LINK` exports (vmast
reads everything it needs from `.env.deploy`).

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
its slugified name. Set `IMAGE_TAG` in the deploy job to whichever tag you want
to run (an env name, a branch slug, or a specific SHA).
