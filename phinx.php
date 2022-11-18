<?php

// @TODO change if different envs are required
$config = include 'config.php';

return
    [
    'paths' => [
        'migrations' => __DIR__ . '/db/migrations',
        'seeds' => __DIR__ . '/db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => $config['db']['host'],
            'name' => $config['db']['dbname'],
            'user' => $config['db']['user'],
            'pass' => $config['db']['pass'],
            'port' => '3306',
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation',
];
