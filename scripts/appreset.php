<?php

chdir(__DIR__ . '/../');

echo 'Dropping database...' . PHP_EOL;
$config = include 'config.php';

try {
    $db = $config['db'];
    $pdo = new PDO('mysql:host=' . $db['host'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->query("DROP DATABASE IF EXISTS " . $db['dbname'])->execute();
} catch (\Exception $e) {
    var_dump($e);die;
    echo 'Database related exception.' . PHP_EOL;
    echo $e . PHP_EOL;
    exit;
}
echo 'Removing .env file...' . PHP_EOL;
unlink('.env');

echo 'App reset done.' . PHP_EOL;

echo PHP_EOL;
