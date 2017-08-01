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
        //禁用原来css样式
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
            ],
        ],
        //禁用原来css样式

        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'loginUrl'=>['member/login'],
            //'identityClass' => 'frontend\models\Member',
            'identityClass' => \frontend\models\Member::className(),
            'enableAutoLogin' => true,
            //'authTimeout' => 1*60,
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
            'rules' => [
            ],
        ],
        'sms'=>[
            'class'=>\frontend\components\AliyunSms::className(),
            'accessKeyId'=>'LTAIJJGxBOfw5OOm',
            'accessKeySecret'=>'d7isJEpMDT6U3kaXFRYa7ckKjMocaB',
            'signName'=>'卢先生小面',
            'templateCode'=>'SMS_80110062'
        ]
    ],
    'params' => $params,
];
