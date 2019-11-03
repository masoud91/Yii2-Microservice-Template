#!groovy​

node {
    def appImage

    stage('Clone repository') {
        /* Let's make sure we have the repository cloned to our workspace */
        cleanWs()
        checkout scm
    }

    stage('config project & install Libraries') {
        sh 'composer install'
        configFileProvider([
            configFile(fileId: 'msid_comment_test', variable: 'TEST_LOCAL'),
            configFile(fileId: 'msid_comment_main', variable: 'MAIN_LOCAL'),
        ]) {
            sh 'mv $TEST_LOCAL config/test-local.php'
            sh 'mv $MAIN_LOCAL config/main-local.php'
            sh 'chmod 775 config/main-local.php config/test-local.php'
        }
    }

    stage('code quality & Report') {
        sh 'ant static-analysis'
        checkstyle([
            pattern: 'build/logs/checkstyle.xml'
        ])
        pmd([
            canRunOnFailed: true,
            pattern: 'build/logs/pmd.xml'
        ])
        dry([
            canRunOnFailed: true,
            pattern: 'build/logs/pmd-cpd.xml'
        ])

        plot([
            csvFileName: 'plot-a.csv',
            csvSeries: [[
                displayTableFlag: false,
                exclusionValues: 'Lines of Code (LOC), Comment Lines of Code (CLOC), Non-Comment Lines of Code (NCLOC), Logical Lines of Code (LLOC)',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING',
                url: ''
            ]],
            group: 'phploc',
            numBuilds: '100',
            style: 'line',
            title: 'A - Lines of code',
            yaxis: 'Lines of Code'
        ])

        plot([
            csvFileName: 'plot-b.csv',
            csvSeries: [[
                displayTableFlag: false,
                exclusionValues: 'Directories, Files, Namespaces',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING',
                url: ''
            ]],
            group: 'phploc',
            numBuilds: '100',
            style: 'line',
            title: 'B - Structures Containers',
            yaxis: 'Count'
        ])

        plot([
            csvFileName: 'plot-c.csv',
            csvSeries: [[
                displayTableFlag: false,
                exclusionValues: 'Average Class Length (LLOC), Average Method Length (LLOC), Average Function Length (LLOC)',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING',
                url: ''
            ]],
            group: 'phploc',
            numBuilds: '100',
            style: 'line',
            title: 'C - Average Length',
            yaxis: 'Average Lines of Code'
        ])
    }

    /* if (env.BRANCH_NAME == "deployment") { } */

    stage('Test & Coverage Reports') {
        sh 'ant test-unit'
        sh 'ant test-acceptance'

        step([
            $class: 'CloverPublisher',
            cloverReportDir: 'tests/_output/',
            cloverReportFileName: 'coverage.xml',
            healthyTarget: [methodCoverage: 70, conditionalCoverage: 80, statementCoverage: 80],
            unhealthyTarget: [methodCoverage: 50, conditionalCoverage: 50, statementCoverage: 50],
            failingTarget: [methodCoverage: 0, conditionalCoverage: 0, statementCoverage: 0]
        ])

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

    stage('Generate API Documentation') {
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

    try {
        stage('Build Container') {
//             sh 'mkdir data'
            appImage = docker.build("idco/msid")
        }

        stage ('Run container') {
            sh 'docker-compose  -f docker-compose.yml up -d'
            sleep(time:60, unit:"SECONDS")
        }

        stage('Running Integration Tests') {
            sh 'ant behat'
        }

        stage ('Cleanup Container') {
            sh 'docker-compose -f docker-compose.yml down'
        }

    } catch (Exception ex) {
        stage ('Cleanup Container') {
            sh 'docker-compose -f docker-compose.yml down'
        }
    }

}
