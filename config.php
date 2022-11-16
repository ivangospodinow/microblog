<?php

function ensureEnv(string $key)
{
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    exit('.env:' . $key . ' is missing');
}

return [

    // as per the slim3 guide
    'displayErrorDetails' => true,
    'addContentLengthHeader' => false,

    'db' => [
        'host' => ensureEnv('DB_HOST'),
        'user' => ensureEnv('DB_USER'),
        'pass' => ensureEnv('DB_PASS'),
        'dbname' => ensureEnv('DB_DBNAME'),
    ],
    'factory' => [
        'db' => \App\Factory\PdoFactory::class,
    ],
];
