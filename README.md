# OAuth MicroService
This micro service is in charge of generating and verifying OAuth Tokens.

#### Here is setup TODO:

##### 1- Change baseUri port number in docs/api.raml
Use naming convention of 30XXX start From 30081 and increase the number for each service.

##### 2- Enable debug 
- create `runtime` folder in project root with chmod of `775`.
- add the following lines in `config/main-local.php`.
```
if (!YII_ENV_TEST) {
   $config['bootstrap'][] = 'debug';
   $config['modules']['debug'] = [
       'class' => 'yii\debug\Module',
       'allowedIPs' => ['*'],
       'panels' => [
           'mongodb' => [
               'class' => 'yii\mongodb\debug\MongoDbPanel',
               'db' => 'mongodb',
           ],
       ]
   ];
}

return $config
```
also you need to create an `assets` folder inside web directory with chmod of `775` as well.

##### 3- config deployment environment  
setup modules configurations (like db and etc) in main-local.  
for module names use naming convention of `[msid_mydb]` like `msid_activity`.

##### 4- config test environment  
setup test modules configurations (like db and etc) in `config/test-local.php`.
for module names use naming convention of `[msid_mydb_test]` like `msid_activity_test`  
notice that we use different DBs for test and deployment.  
also we need to copy and paste `config/main.php` in to `config/test.php` but we can use 
different module configurations, for example `useFileTransport` could be set to true in 
`config/test.php` for `mail` module while it is false in `config/main.php`.

##### 5- setup codeception test suites
change ports number of `REST url` and `c3_url` in `tests/api.suite.yml`
Use naming convention of 20XXX start From 20081 and increase the number for each service.  
notice that XXX part should be equal to 30XXX in number 1 (in raml.api)

##### 6- setup behat test config
change ports number of `baseURL` in `tests/features/bootstrap/config.php`
set it exactly to the port number you used in number 1 (in raml.api)
