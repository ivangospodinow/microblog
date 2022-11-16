<?php

namespace App\Factory;

use Slim\Container;

abstract class AbstractFactory
{
    abstract public function __invoke(Container $config);
}
