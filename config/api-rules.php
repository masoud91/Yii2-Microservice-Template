<?php
return [

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['hello'],
        'patterns' => [
            'GET world' => 'world'
        ],
        'pluralize' => false
    ]

];
