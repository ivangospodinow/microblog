<?php

namespace App\Factory;

use App\Service\ServiceLocatorService;

abstract class AbstractFactory
{
    abstract public function __invoke(ServiceLocatorService $serviceLocator);
}
