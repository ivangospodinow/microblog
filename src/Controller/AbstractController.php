<?php

namespace App\Factory;

use Slim\Container;

abstract class AbstractController
{
    protected $config;

    public function __construct(Container $config)
    {
        $this->config = $config;
    }
}
