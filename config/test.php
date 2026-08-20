<?php

use App\Interfaces\Http\ErrorHandler\ApiErrorHandler;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

return [
    'id' => 'api-tests',
    'basePath' => dirname(__DIR__),
    'language' => 'en-US',
    'controllerNamespace' => 'App\Interfaces\Http\Controllers',
    'components' => [
        'db' => $db,
        'response' => [
            'format' => \yii\web\Response::FORMAT_JSON,
        ],
        'errorHandler' => [
            'class' => ApiErrorHandler::class,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [
                'GET api/v1/health' => 'health/index',
                'GET api/v1/health/ready' => 'health/ready',
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
    ],
    'params' => $params,
];
