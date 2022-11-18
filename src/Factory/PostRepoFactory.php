<?php

namespace App\Factory;

use App\Repo\PostRepo;
use Slim\Container;

class PostRepoFactory extends AbstractFactory
{
    public function __invoke(Container $config)
    {
        return new PostRepo($config->db);
    }
}
