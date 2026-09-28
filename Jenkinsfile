// Pull-based production deploy for vmast-docker.
//
// Runs deploy.sh on an agent that has docker (with the compose plugin) and the
// 1password CLI `op`. deploy.sh pulls the images the docker-build workflow
// published and starts the stack with secrets resolved from 1password.
//
// Adapt to your Jenkins:
//   - agent label 'docker-deploy'          -> a node with docker + op installed
//   - credential 'vmast-op-service-account' -> Secret text: OP_SERVICE_ACCOUNT_TOKEN
//   - credential 'dockerhub'                -> Username/password for Docker Hub
// If you deploy to a remote host instead of the agent, replace the Deploy stage
// body with an ssh step that runs deploy.sh on that host (see DEPLOY.md).

pipeline {
  agent { label 'docker-deploy' }

  parameters {
    string(name: 'DEPLOY_ENV', defaultValue: 'production',
           description: '1password section to read (production, staging, ...)')
    string(name: 'IMAGE_TAG', defaultValue: 'latest',
           description: 'Docker Hub tag to deploy (commit SHA, branch slug, or latest)')
  }

  environment {
    OP_SERVICE_ACCOUNT_TOKEN = credentials('vmast-op-service-account')
  }

  options {
    disableConcurrentBuilds()
    timestamps()
  }

  stages {
    stage('Checkout') {
      steps { checkout scm }
    }

    stage('Registry login') {
      steps {
        withCredentials([usernamePassword(credentialsId: 'dockerhub',
                                           usernameVariable: 'DH_USER',
                                           passwordVariable: 'DH_PASS')]) {
          sh 'echo "$DH_PASS" | docker login -u "$DH_USER" --password-stdin'
        }
      }
    }

    stage('Deploy') {
      steps {
        sh '''
          export DEPLOY_ENV="${DEPLOY_ENV}"
          export IMAGE_TAG="${IMAGE_TAG}"
          chmod +x ./deploy.sh
          ./deploy.sh
        '''
      }
    }
  }

  post {
    always { sh 'docker logout || true' }
  }
}
