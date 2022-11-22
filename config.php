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
    'imagesDir' => __DIR__ . '/public/img',
    'imagesPublicPath' => '/img',

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
        'authUser' => \App\Factory\AuthUserFactory::class,
        'imageStoreService' => \App\Factory\ImageStoreServiceFactory::class,
    ],

    'routes' => [
        [
            'type' => 'get',
            'uri' => '/api/users',
            'callback' => [\App\Controller\UsersApiController::class, 'index'],
            'public' => false,
        ],
        [
            'type' => 'post',
            'uri' => '/api/users',
            'callback' => [\App\Controller\UsersApiController::class, 'create'],
            'public' => false,
        ],
        [
            'type' => 'put',
            'uri' => '/api/users/{id}',
            'callback' => [\App\Controller\UsersApiController::class, 'update'],
            'public' => false,
        ],
        [
            'type' => 'delete',
            'uri' => '/api/users/{id}',
            'callback' => [\App\Controller\UsersApiController::class, 'delete'],
            'public' => false,
        ],
        [
            'type' => 'post',
            'uri' => '/api/user/login',
            'callback' => [\App\Controller\LoginApiController::class, 'login'],
            'public' => true,
        ],
        [
            'type' => 'get',
            'uri' => '/api/posts',
            'callback' => [\App\Controller\PostsApiController::class, 'index'],
            'public' => true,
        ],
        [
            'type' => 'post',
            'uri' => '/api/posts',
            'callback' => [\App\Controller\PostsApiController::class, 'create'],
            'public' => false,
        ],
        [
            'type' => 'put',
            'uri' => '/api/posts/{id}',
            'callback' => [\App\Controller\PostsApiController::class, 'update'],
            'public' => false,
        ],
        [
            'type' => 'delete',
            'uri' => '/api/posts/{id}',
            'callback' => [\App\Controller\PostsApiController::class, 'delete'],
            'public' => false,
        ],
        [
            'type' => 'get',
            'uri' => '/api/posts/months',
            'callback' => [\App\Controller\PostsApiController::class, 'months'],
            'public' => true,
        ],
    ],
];
