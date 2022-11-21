<?php

use App\Service\ServiceLocatorService;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

$config = include 'config.php';
$app = new \Slim\App(['settings' => $config]);
$container = $app->getContainer();
$serviceLocator = new ServiceLocatorService($container);
$container->serviceLocator = $serviceLocator;

// CORS

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("hello world");
    return $response;
});

foreach ($config['routes'] as $route) {
    $method = $route['type'];
    $app->$method($route['uri'], function (Request $request, Response $response, array $args) use ($route) {
        // @TODO remove
        // introduce a random delay, to help development
        // sleep(rand(0, 3));

        $params = $route['type'] === 'get' ? $request->getQueryParams() : $request->getParsedBody();

        // @TODO check for url query from object for frontend implementation
        if ($route['type'] === 'get' && isset($params['json'])) {
            $params = json_decode(urldecode($params['json']), true);
        }

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
