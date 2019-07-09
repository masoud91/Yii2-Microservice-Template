<?php

$apiRules = array_merge(
    ['POST oauth2/<action:\w+>' => 'oauth2/rest/<action>'],
    require(__DIR__ . '/api-rules.php')
);

return [
    'id' => 'msid',

    // the basePath of the application will be the `micro-app` directory
    'basePath' => dirname(__DIR__),

    // this is where the application will find all controllers
    'controllerNamespace' => 'micro\controllers',

    // set an alias to enable autoloading of classes from the 'micro' namespace
    'aliases' => [
        '@micro' => dirname(__DIR__),
    ],

    'language' => 'fa-IR',
    'timeZone' => 'Asia/Tehran',

    'components' => [

        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => $apiRules
        ],

        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],

        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
                'model*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'models' => 'models.php',
                        'models/error' => 'error.php',
                    ],
                ],
            ],
        ],
    ],
];
