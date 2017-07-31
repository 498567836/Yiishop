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
            'accessKeyId'=>'LTAIF4JlJQ0cGxTa',
            'accessKeySecret'=>'RtXBSHEEF4PzyCVrUtz2mn8FkmOT9B',
            'signName'=>'伍先生茶馆',
            'templateCode'=>'SMS_80145053'
        ]
    ],
    'params' => $params,
];
