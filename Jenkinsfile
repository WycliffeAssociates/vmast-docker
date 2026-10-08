// Pull-based production deploy for vmast-docker, modeled on the WACS_deploy_dev
// freestyle job (Publish Over SSH -> run deploy.sh on the host).
//
// Flow: check out the repo on the `docker` node, bind the 1password token to
// OP_SERVICE_ACCOUNT_TOKEN, then Publish Over SSH copies the compose files,
// deploy.sh and .env.deploy to the host and runs deploy.sh there. op and docker
// run on the host; the images are pulled from Docker Hub; secrets are resolved
// on the host and never written to a file.
//
// Wire to your Jenkins:
//   - agent label 'docker'
//   - the job's SCM checks out this repo at the branch to deploy
//   - the job parameters below. Each default is `params.X ?: <repo default>`,
//     so whatever value the job already has is kept as its default and only a
//     brand-new job falls back to the repo default. The host, directory and
//     credential ID default to blank here, so their real values live only in
//     each Jenkins job, never in this repo. Set them in the job configuration
//     or with "Build with Parameters"; note that building with parameters
//     makes the values used the job's new defaults.
//
// The freestyle equivalent is documented in DEPLOY.md.

pipeline {
  agent { label 'docker' }

  parameters {
    string(name: 'DEPLOY_ENV', defaultValue: params.DEPLOY_ENV ?: 'dev',
           description: '1password section and env image tag (dev, prod, ...)')
    string(name: 'IMAGE_TAG', defaultValue: params.IMAGE_TAG ?: '',
           description: 'Image tag to deploy (blank = DEPLOY_ENV)')
    string(name: 'VMAST_SSH_SERVER', defaultValue: params.VMAST_SSH_SERVER ?: '',
           description: 'Name of the Publish Over SSH "SSH Server" to deploy to')
    string(name: 'VMAST_REMOTE_DIR', defaultValue: params.VMAST_REMOTE_DIR ?: '',
           description: 'Deploy directory on that host')
    string(name: 'VMAST_OP_CREDENTIALS_ID', defaultValue: params.VMAST_OP_CREDENTIALS_ID ?: '',
           description: 'ID of the Secret text credential holding the 1Password service-account token')
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
          // All deploy settings come from the job parameters; the sensitive ones
          // default to blank in this repo, so stop before touching any host.
          def missing = []
          for (name in ['DEPLOY_ENV', 'VMAST_SSH_SERVER', 'VMAST_REMOTE_DIR', 'VMAST_OP_CREDENTIALS_ID']) {
            if (!params[name]?.toString()?.trim()) { missing << name }
          }
          if (missing) {
            error("Missing job parameter(s): ${missing.join(', ')}. " +
                  "Set them in the job configuration or with 'Build with Parameters'.")
          }
          def deployEnv = params.DEPLOY_ENV.trim()
          def sshServer = params.VMAST_SSH_SERVER.trim()
          def remoteDir = params.VMAST_REMOTE_DIR.trim()
          def opCredentialsId = params.VMAST_OP_CREDENTIALS_ID.trim()
          def imageTag = params.IMAGE_TAG?.toString()?.trim() ?: deployEnv
          withCredentials([string(credentialsId: opCredentialsId,
                                  variable: 'OP_SERVICE_ACCOUNT_TOKEN')]) {
            // Runs on the remote host. In a pipeline, Publish Over SSH expands
            // ${...} in execCommand from the build's base environment, which does
            // not include variables bound by withCredentials - so the token is
            // put into the command here instead. Jenkins masks it in the console
            // log and warns about Groovy interpolation of a secret; that is
            // expected. The token reaches the host inside the SSH command, as in
            // the WACS_deploy_dev freestyle job, and is never written to a file.
            def remoteCmd = """#!/bin/bash
cd ${remoteDir}
export DEPLOY_ENV=${deployEnv}
export IMAGE_TAG=${imageTag}
export OP_SERVICE_ACCOUNT_TOKEN='${env.OP_SERVICE_ACCOUNT_TOKEN}'
source deploy.sh"""

            // failOnError: a failed transfer or exec fails the build instead of
            // leaving it UNSTABLE, so a broken deploy cannot look green.
            sshPublisher(failOnError: true, publishers: [
              sshPublisherDesc(
                configName: sshServer,
                verbose: true,
                transfers: [
                  sshTransfer(
                    sourceFiles: 'docker-compose.yml,docker-compose.prod.yml,deploy.sh,.env.deploy',
                    remoteDirectory: remoteDir,
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
