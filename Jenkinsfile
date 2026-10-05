// Pull-based production deploy for vmast-docker, modeled on the WACS_deploy_dev
// freestyle job (Publish Over SSH -> run deploy.sh on the host).
//
// Flow: check out the repo on the `docker` node, bind the 1password token to
// OP_SERVICE_ACCOUNT_TOKEN, then Publish Over SSH copies the compose files,
// deploy.sh and .env.deploy to the host and runs deploy.sh there. op and docker
// run on the host; the images are pulled from Docker Hub; secrets are resolved
// on the host and never written to a file.
//
// Wire to your Jenkins (values below are placeholders - match WACS_deploy_dev):
//   - agent label 'docker'
//   - SSH server '<ssh-server-name>'            -> Publish Over SSH "SSH Server" name
//   - REMOTE_DIR '<deploy dir>'              -> deploy dir on that host
//   - credential 'vmast-op-service-account'-> Secret text: OP_SERVICE_ACCOUNT_TOKEN
//   - the job's SCM checks out this repo at the branch to deploy
//
// The freestyle equivalent is documented in DEPLOY.md.

pipeline {
  agent { label 'docker' }

  parameters {
    string(name: 'DEPLOY_ENV', defaultValue: 'dev',
           description: '1password section and env image tag (dev, prod, ...)')
    string(name: 'IMAGE_TAG', defaultValue: '',
           description: 'Image tag to deploy (blank = DEPLOY_ENV)')
  }

  environment {
    SSH_SERVER = '<ssh-server-name>'
    REMOTE_DIR = '<deploy dir>'
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
        script {
          def imageTag = params.IMAGE_TAG?.trim() ? params.IMAGE_TAG.trim() : params.DEPLOY_ENV
          // Runs on the remote host. ${OP_SERVICE_ACCOUNT_TOKEN} is substituted
          // by Publish Over SSH from the bound credential, so it is escaped here.
          def remoteCmd = """#!/bin/bash
cd ${env.REMOTE_DIR}
export DEPLOY_ENV=${params.DEPLOY_ENV}
export IMAGE_TAG=${imageTag}
export OP_SERVICE_ACCOUNT_TOKEN=\${OP_SERVICE_ACCOUNT_TOKEN}
source deploy.sh"""

          withCredentials([string(credentialsId: 'vmast-op-service-account',
                                  variable: 'OP_SERVICE_ACCOUNT_TOKEN')]) {
            sshPublisher(publishers: [
              sshPublisherDesc(
                configName: env.SSH_SERVER,
                verbose: true,
                transfers: [
                  sshTransfer(
                    sourceFiles: 'docker-compose.yml,docker-compose.prod.yml,deploy.sh,.env.deploy',
                    remoteDirectory: env.REMOTE_DIR,
                    execCommand: remoteCmd,
                    execTimeout: 240000
                  )
                ]
              )
            ])
          }
        }
      }
    }
  }
}
