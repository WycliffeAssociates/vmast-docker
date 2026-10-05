// Pull-based production deploy for vmast-docker.
//
// Runs deploy.sh on the Jenkins agent. The agent only needs docker (to run the
// containerized op CLI and the compose client) - op is NOT installed on the
// node. Secrets are resolved from 1password into the job's environment and the
// containers are started on the remote docker host named by DEPLOY_DOCKER_HOST,
// so that host never receives the 1password token or any env file.
//
// Wire these to your Jenkins:
//   - agent label 'docker'                   -> a node that can run containers
//   - credential 'vmast-op-service-account'  -> Secret text: OP_SERVICE_ACCOUNT_TOKEN
//   - credential 'vmast-prod-ssh'            -> SSH private key for ssh:// docker
//   - param DEPLOY_DOCKER_HOST               -> ssh://user@host of the prod daemon
// If the agent itself is the docker host, drop the sshagent block and leave
// DEPLOY_DOCKER_HOST empty.

pipeline {
  agent { label 'docker' }

  parameters {
    string(name: 'DEPLOY_ENV', defaultValue: 'prod',
           description: '1password section and env image tag (prod, dev, ...)')
    string(name: 'IMAGE_TAG', defaultValue: '',
           description: 'Override image tag to deploy (blank = DEPLOY_ENV)')
    string(name: 'DEPLOY_DOCKER_HOST', defaultValue: 'ssh://deploy@vmast-prod',
           description: 'Docker daemon to deploy to (blank = this agent)')
  }

  options {
    disableConcurrentBuilds()
    timestamps()
  }

  stages {
    stage('Checkout') {
      steps { checkout scm }
    }

    stage('Deploy') {
      steps {
        sshagent(['vmast-prod-ssh']) {
          withCredentials([string(credentialsId: 'vmast-op-service-account',
                                  variable: 'OP_SERVICE_ACCOUNT_TOKEN')]) {
            sh '''
              set -eu
              export DEPLOY_ENV="${DEPLOY_ENV}"
              if [ -n "${IMAGE_TAG}" ]; then export IMAGE_TAG="${IMAGE_TAG}"; fi
              if [ -n "${DEPLOY_DOCKER_HOST}" ]; then export DOCKER_HOST="${DEPLOY_DOCKER_HOST}"; fi
              chmod +x ./deploy.sh
              ./deploy.sh
            '''
          }
        }
      }
    }
  }
}
