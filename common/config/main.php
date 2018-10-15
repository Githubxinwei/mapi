<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone'   => 'Asia/Shanghai',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\DbCache',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['info'],
                    'categories' => ['notify'],
                    'logVars' => [],
                    'prefix' => function ($message) {
                        return Yii::$app->request->url;
                    },
                    'exportInterval' => 1,
                ],
            ],
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
        ],
    ],
];
