#!groovy​

node {
    def app

    stage('Clone repository') {
        /* Let's make sure we have the repository cloned to our workspace */
        checkout scm
    }

    stage('Update Project Libraries') {
        sh 'composer install'
    }

    stage('Run Test Steps') {
        sh 'ant full-build'
    }

    publishHTML([
        allowMissing: false,
        alwaysLinkToLastBuild: false,
        keepAll: false,
        reportDir: 'tests/_output/',
        reportFiles: 'report.html',
        reportName: 'HTML Report',
        reportTitles: ''
     ])
}
