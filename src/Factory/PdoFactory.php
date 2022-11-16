<?php

namespace App\Factory;

use PDO;
use Slim\Container;

class PdoFactory
{
    public function __invoke(Container $config)
    {
        $db = $config['settings']['db'];
        $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
            $db['user'], $db['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
}
