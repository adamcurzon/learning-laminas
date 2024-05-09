<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'db' => [
        'driver' => 'Pdo',
        'dsn'    => sprintf('sqlite:%s/data/learning-laminas.db', realpath(getcwd())),
    ],
    'redis-cache' => [
        'adapter' => 'redis',
        'options' => [
            'server' => [
                'host' => '127.0.0.1',
                'port' => 6379,
            ],
            'ttl' => 3600,
        ],
    ]
];
