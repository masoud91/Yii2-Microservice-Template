<?php
$config = [
    'id' => 'msid-test',

    // the basePath of the application will be the `micro-app` directory
    'basePath' => dirname(__DIR__),

    // this is where the application will find all controllers
    'controllerNamespace' => 'micro\controllers',

    // set an alias to enable autoloading of classes from the 'micro' namespace
    'aliases' => [
        '@micro' => dirname(__DIR__),
    ],

    'components' => [

        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['hello'],
                    'patterns' => [
                        'GET world' => 'world'
                    ],
                    'pluralize' => false
                ]
            ],
        ],

        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
    ],
];

return yii\helpers\ArrayHelper::merge(
    $config,
    require(__DIR__ . '/../config/main-local.php')
);