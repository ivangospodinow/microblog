<?php

namespace App\Factory;

use App\Service\AuthUser;
use App\Service\ServiceLocatorService;

class AuthUserFactory extends AbstractFactory
{
    public function __invoke(ServiceLocatorService $serviceLocator)
    {
        $service = new AuthUser();
        if (isset($_SERVER['HTTP_AUTHORIZATION']) && $_SERVER['HTTP_AUTHORIZATION']) {
            $service->fromSession($_SERVER['HTTP_AUTHORIZATION']);

        }
        return $service;
    }
}
