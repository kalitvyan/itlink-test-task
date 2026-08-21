<?php

use App\Domain\Repository\CarRepositoryInterface;
use App\Infrastructure\Persistence\Postgres\CarRepository;
use App\Interfaces\Http\ErrorHandler\ApiErrorHandler;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'App\Interfaces\Http\Controllers',
    'container' => [
        'singletons' => [
            \yii\db\Connection::class => $db,
        ],
        'definitions' => [
            CarRepositoryInterface::class => CarRepository::class,
        ],
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => $_ENV['COOKIE_VALIDATION_KEY'],
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'format' => \yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'errorHandler' => [
            'class' => ApiErrorHandler::class,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'GET api/v1/health' => 'health/index',
                'GET api/v1/health/ready' => 'health/ready',
                'POST api/v1/car/create' => 'car/create',
                'GET api/v1/car/list' => 'car/list',
                'GET api/v1/car/<id:\d+>' => 'car/view',
            ],
        ],
    ],
    'params' => $params,
];

return $config;
