<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$config = include 'config.php';
$app = new \Slim\App(['settings' => $config]);

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("hello world");
    return $response;
});

$container = $app->getContainer();

// init factories
foreach ($config['factory'] as $nameKey => $className) {
    $container[$nameKey] = function (Slim\Container $config) use ($className) {
        $instance = new $className();
        return $instance($config);
    };
}

return $app;
