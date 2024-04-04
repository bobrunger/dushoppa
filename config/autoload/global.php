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
        'dsn' => sprintf('%s:dbname=%s;host=%s;charset=utf8', $_ENV['DB_DRIVER'], $_ENV['DB_NAME'], $_ENV['DB_HOST']),
        'username' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASS'],
    ],
];
