<?php

return [
    'class' => \yii\db\Connection::class,
    'dsn' => sprintf(
        'pgsql:host=%s;port=%s;dbname=%s',
        $_ENV['DB_HOST'],
        $_ENV['DB_PORT'],
        $_ENV['TEST_DB_NAME'] ?? $_ENV['DB_NAME'] . '_test',
    ),
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8',
];
