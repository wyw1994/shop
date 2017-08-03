<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            //'cookieValidationKey' => 'fcuVvgFv0Vex88Qm5N2-h6HH5anM4HEd',
        ],
        'user' => [
//            'identityClass' => 'common\models\User',
        'identityClass'=>'frontend\models\Member',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'suffix'=>'.html',
            'rules' => [
            ],
        ],
//        'redis'=>[
//          'class'=>'yii\redis\Connection',
//            'hostname'=>'loaclhost',
//            'port'=>6379,
//            'database'=>0,
//        ],
        //配置短信组件
        'sms'=>[
            'class'=>\frontend\components\Sms::className(),
            'app_key'=>'24478566',
            'app_secret'=>'05646ef2609165f3ecf9ac109c9ad65b',
            'sign_name'=>'网站开发学习',
            'template_code'=>'SMS_71885209'
        ],
    ],
    'params' => $params,
];
