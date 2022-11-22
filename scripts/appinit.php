<?php

chdir(__DIR__ . '/../');

echo PHP_EOL;

echo 'Application is initting...' . PHP_EOL;

echo 'Checking .env file' . PHP_EOL;
if (!file_exists('.env')) {
    echo 'Creating .env file...' . PHP_EOL . PHP_EOL;

    copy('.env.example', '.env');
    echo file_get_contents('.env') . PHP_EOL . PHP_EOL;
    echo '.env file created ' . (file_exists('.env') ? 'successfull' : 'failed') . PHP_EOL;
} else {
    echo '.env file already created.' . PHP_EOL;
}
echo PHP_EOL;

echo 'Checking database...' . PHP_EOL;

$config = include 'config.php';

try {
    $db = $config['db'];
    $pdo = new PDO('mysql:host=' . $db['host'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->query("CREATE DATABASE IF NOT EXISTS " . $db['dbname'])->execute();
    $pdo->query("use " . $db['dbname'])->execute();

    $statement = $pdo->query('show tables');
    $statement->execute();

    if (is_array($statement->fetchAll())) {
        echo 'Database is up and running.' . PHP_EOL;
    } else {
        echo 'Database creation failed' . PHP_EOL;
    }

} catch (\Exception $e) {
    echo 'Database related exception.' . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    echo 'Make sure your database server is running' . PHP_EOL;
    exit;
}

echo PHP_EOL;
