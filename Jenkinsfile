pipeline {
    agent any
    environment {
        NGINX = ''
        PHP = ''
        BRANCH_NAME = "${GIT_BRANCH.split('/')[1] == 'feature' ? 'develop' : GIT_BRANCH.split('/')[1]}"
    }

    stages {

        // git으로부터 소스 다운하는 stage
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Deploy docker compose'){
            steps{
                script {
                    sshPublisher(
                        continueOnError: false,
                        failOnError: true,
                        publishers: [
                            sshPublisherDesc(
                                configName: "213",
                                verbose: true,
                                transfers: [
                                    sshTransfer(
                                        execCommand: "cd /home/exam-back/ && git pull && sudo composer install --ignore-platform-reqs && sudo chmod 0777 -R storage/"
                                    ),
                                ]
                            )
                        ]
                    )
                }

            }
        }

        stage('Complete') {
            steps {
                sh "echo 'The end'"
            }
        }
    }
}
