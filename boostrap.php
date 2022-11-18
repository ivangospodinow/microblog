<?php

use App\Service\ServiceLocatorService;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

$config = include 'config.php';
$app = new \Slim\App(['settings' => $config]);
$container = $app->getContainer();
$serviceLocator = new ServiceLocatorService($container);
$container->serviceLocator = $serviceLocator;

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("hello world");
    return $response;
});

foreach ($config['routes'] as $route) {
    $method = $route['type'];
    $app->$method($route['uri'], function (Request $request, Response $response, array $args) use ($route) {
        $params = $route['type'] === 'get' ? $request->getQueryParams() : $request->getParsedBody();
        $callback = $route['callback'];
        return $response->withJson(call_user_func_array(
            [
                new $callback[0]($this->serviceLocator),
                $callback[1],
            ],
            [$params, $args]
        ));
    });
}

// init factories
foreach ($config['factory'] as $nameKey => $className) {
    $container[$nameKey] = function (Slim\Container $config) use ($className) {
        $instance = new $className();
        return $instance($config->serviceLocator);
    };
}

return $app;
