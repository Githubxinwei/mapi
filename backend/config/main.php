<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'sourceLanguage' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'version' => '1.0',
    'modules' => [
        'v1' => [
            'class' => 'backend\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf_backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'keyPrefix'=>'backend_',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
       /*'user' => [
            'identityClass' => 'backend\models\Admin',
           'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
           'on beforeLogout' => function($event){
               $adminUser = $event->identity;
                $adminUser->setScenario('beforeLogout'); //（自定义）设置验证规则指定场景
                \Yii::$app->getSession('session')->destroy();
           }

       ],*/
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/api-class','v1/api-private','v1/api-coach','v1/api-venue','v2/api-employee-login','v2/api-visitors']],
            ],
        ],
    ],
    'params' => $params,
];
