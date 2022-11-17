<?php

if (!file_exists('.env')) {
    exit('.env file is missing. Please copy .env.example and fill the required info.');
}

require_once 'vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$ensureEnv = function (string $key) {
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    exit('.env:' . $key . ' is missing');
};

return [

    // as per the slim3 guide
    'displayErrorDetails' => true,
    'addContentLengthHeader' => false,

    'db' => [
        'host' => $ensureEnv('DB_HOST'),
        'user' => $ensureEnv('DB_USER'),
        'pass' => $ensureEnv('DB_PASS'),
        'dbname' => $ensureEnv('DB_DBNAME'),
    ],
    'factory' => [
        'db' => \App\Factory\PdoFactory::class,
        'userRepo' => \App\Factory\UserRepoFactory::class,
        'postRepo' => \App\Factory\PostRepoFactory::class,
    ],
];
