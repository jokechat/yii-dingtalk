<?php

$params   = array_merge_recursive(
		require(__DIR__ . '/params.php'),
		require(__DIR__ . '/dingtalk.php'),
		require(__DIR__ . '/dingtalk_title.php'),
		require(__DIR__ . '/service_api.php')
		);
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '从你的github 获取token',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
       		'enableStrictParsing' => false,
            'showScriptName' => false,
//       		'suffix' => '.html',
            'rules' => [
            		['class' => 'yii\rest\UrlRule', 'controller' => 'v1\module'],
            ],
        ],
    	
    ],
	
	//别名 rbac配置信息 角色权限控制  会话控制
		
	//配置modules
	'modules' => [
			//test 充当测试
			'test' => [
					'class' => 'app\modules\test\test',
			],
	],
		
// 	//设置前端资源别名
// 	'aliases' => [
// 			'@dingtalk_basepath' => 'views/skin',
// 	],
		
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
