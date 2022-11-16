<?php
chdir(__DIR__ . '/..');

require_once 'vendor/autoload.php';

if (!file_exists('.env')) {
    exit('.env file is missing. Please copy .env.example and fill the required info.');
}

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$app = include 'boostrap.php';
$app->run();
