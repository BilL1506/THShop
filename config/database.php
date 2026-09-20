<?php
use Illuminate\Support\Str;
return [
    'default' => 'web',
    'connections' => [
        'game' => [
            'driver' => 'mysql', 'url' => null,
            'host' => env('GAME_DB_HOST', '127.0.0.1'), 'port' => env('GAME_DB_PORT', '3306'),
            'database' => env('GAME_DB_DATABASE', 'talesrunner_game'), 'username' => env('GAME_DB_USERNAME', 'root'),
            'password' => env('GAME_DB_PASSWORD', ''), 'unix_socket' => env('GAME_DB_SOCKET', ''),
            'charset' => env('GAME_DB_CHARSET', 'utf8mb4'), 'collation' => env('GAME_DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '', 'prefix_indexes' => true, 'strict' => true, 'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([PDO::ATTR_EMULATE_PREPARES => false]) : [],
        ],
        'web' => [
            'driver' => 'mysql', 'url' => null,
            'host' => env('WEB_DB_HOST', '127.0.0.1'), 'port' => env('WEB_DB_PORT', '3306'),
            'database' => env('WEB_DB_DATABASE', 'talesrunner_web'), 'username' => env('WEB_DB_USERNAME', 'root'),
            'password' => env('WEB_DB_PASSWORD', ''), 'unix_socket' => env('WEB_DB_SOCKET', ''),
            'charset' => env('WEB_DB_CHARSET', 'utf8mb4'), 'collation' => env('WEB_DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '', 'prefix_indexes' => true, 'strict' => true, 'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([PDO::ATTR_EMULATE_PREPARES => false]) : [],
        ],
    ],
    'migrations' => ['table' => 'migrations', 'update_date_on_publish' => true],
    'redis' => ['client' => env('REDIS_CLIENT', 'phpredis'), 'options' => ['cluster' => env('REDIS_CLUSTER', 'redis'), 'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_')]],
];
