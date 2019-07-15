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

    stage('Publish Reports') {
        publishHTML(target: [
            allowMissing: false,
            alwaysLinkToLastBuild: false,
            keepAll: false,
            reportDir: 'tests/_output/',
            reportFiles: 'report.html',
            reportName: 'Codeception Report',
            reportTitles: 'Codeception Report'
        ])

        publishHTML(target: [
                    allowMissing: false,
                    alwaysLinkToLastBuild: false,
                    keepAll: false,
                    reportDir: 'tests/_output/coverage/',
                    reportFiles: 'dashboard.html',
                    reportName: 'Codeception Coverage',
                    reportTitles: 'Codeception Coverage'
                ])
    }

    stage('Generate Documentation') {
        sh 'raml2html docs/api.raml > docs/_output/index.html'

        publishHTML(target: [
            allowMissing: false,
            alwaysLinkToLastBuild: false,
            keepAll: false,
            reportDir: 'docs/_output/',
            reportFiles: 'index.html',
            reportName: 'RAML Documentation',
            reportTitles: 'RAML Documentation'
        ])
    }
}
