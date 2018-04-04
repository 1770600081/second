<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$adminmenu = require(__DIR__. '/adminmenu.php');
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'index',
    'language' => 'zh-cn',
    'charset' => 'utf-8',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@yr'=>dirname(__DIR__),
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Bopf09mIyqZOntB0-9QNKevs5Uj3N17j',
            
        ],
        'authManager' => [   
                 'class' => 'yii\rbac\DbManager', 
                 'defaultRoles' => ['default'],   
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'idParam' => '__user',  // 区分前后台的session
            'identityCookie' => ['name' => '__user_identity', 'httpOnly' => true],  // 区分前后台的cookie
            'loginUrl' => ['/member/auth'], //前台?跳转地址
        ],
        'admin' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\modules\loserbackstage\models\ShopAdmin',
            'idParam' => '__admin', // 区分前后台的session
            'identityCookie' => ['name' => '__admin_identity', 'httpOnly' => true], // 区分前后台的cookie
            'enableAutoLogin' => true,
            'loginUrl' => ['/loserbackstage/public/login'],//前台?跳转地址
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
              'class' => 'Swift_SmtpTransport',
              'host' => 'smtp.163.com',
              'username' => 'fy1770600081@163.com',
              'password' => 'FY1770600081',
              'port' => '465',
              'encryption' => 'ssl',
          ],
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
            ],
        ],
        
    ],
    'params' => array_merge($params, ['adminmenu' => $adminmenu]),

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
        //设置可以访问的ip
        'allowedIPs' => ['127.0.0.1'],
        // 'allowedIPs' => ['127.0.0.1', '::1'],
    ];
    $config['modules']['loserbackstage']=[
    'class' => 'app\modules\loserbackstage\admin',
    ];

}

return $config;
