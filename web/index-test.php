<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

define('C3_CODECOVERAGE_ERROR_LOG_FILE', __DIR__.'../tests/_output/c3_error.log');
require(__DIR__ . '/../web/c3.php');
define('MY_APP_STARTED', true);

$config = require(__DIR__ . '/../config/test.php');

(new yii\web\Application($config))->run();
