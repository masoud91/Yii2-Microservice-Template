<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

define('C3_CODECOVERAGE_ERROR_LOG_FILE', __DIR__.'../tests/_output/c3_error.log'); //Optional (if not set the default c3 output dir will be used)
require(__DIR__ . '/../web/c3.php');
define('MY_APP_STARTED', true);

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);

(new yii\web\Application($config))->run();
