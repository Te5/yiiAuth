<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '1J3yq7NYgAuJfmx2xqnP2MGG0iB580Yu',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            /*'useFileTransport' => true, //сохраняются в runtime, если true*/
            'transport' => [
                'class'=> 'Swift_SmtpTransport',
                'host'=> 'smtp.gmail.com',
                'username'=> 'myyiiserver@gmail.com',
                'password' => '7JURYZ9wnTri7PR',
                'port'=> '465',
                'encryption'=> 'ssl',
            ],
        ],
        'security' => [
            'passwordHashStrategy' => 'password_hash'
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
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'register' => 'user/create'
            ],
        ],
        'authManager' => [
            'class'=> 'yii\rbac\DBManager',
            'defaultRoles' => ['guest'],
        ],
    'authClientCollection' => [
      'class' => 'yii\authclient\Collection',
      'clients' => [
        'facebook' => [
          'class' => 'yii\authclient\clients\Facebook',
          'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
          'clientId' => '582824695467362',
          'clientSecret' => 'ac98700106c38769050351934f9dd0ab',
          'attributeNames' => ['name', 'email', 'first_name', 'last_name'],

        ],
        'vkontakte' => [
                'class' => 'yii\authclient\clients\VKontakte',
                'clientId' => '6700393',
                'clientSecret' => 'g9sMBVpo7b6IPTqspji7',
                'authUrl' => 'https://oauth.vk.com/authorize?client_id=1&redirect_uri=https://google.com',
                'attributeNames' => ['name', 'email', 'first_name', 'last_name'],
            ],
            'google' => [
                'class' => 'yii\authclient\clients\Google',
                'clientId' => '142230693951-1qkvjqm53oruin69dn9r0rr0m6k3ibpp.apps.googleusercontent.com',
                'clientSecret' => '1OxU1-lwKPUDvYB6WZ6cp2uN',
            ],
            'twitter' => [
                'class' => 'yii\authclient\clients\Twitter',
                'attributeParams' => [
                    'include_email' => 'true'
                ],
                'consumerKey' => 'RucoamXAjPHx8hQZZivxtq3T3',
                'consumerSecret' => '3Ak0t0NmZCb9v6V31ebQ7nPQohGmr2Plikou7ezffdixhXcMcy',
            ],            
        ],        
      ],
    ],        
    
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
