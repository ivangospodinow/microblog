<?php

namespace App\Service;

use ArrayAccess;

class ServiceLocatorService
{
    private $config;

    /**
     * Framework specific container / config array
     *
     * @param ArrayAccess|array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function get($key)
    {
        return $this->config->$key;
    }

    public function has($key)
    {
        return isset($this->config->$key);
    }
}
