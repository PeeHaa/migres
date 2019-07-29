<?php declare(strict_types=1);

return [
    'migrationPath' => __DIR__ . '/../migrations',
    'namespace'     => 'Acme\Namespace',
    'database'      => [
        'name'     => 'test_db',
        'host'     => 'localhost',
        'port'     => 5432,
        'username' => 'username',
        'password' => 'password',
    ],
];
