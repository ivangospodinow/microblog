<?php

namespace App\Factory;

use App\Repo\UserRepo;
use Slim\Container;

class UserRepoFactory extends AbstractFactory
{
    public function __invoke(Container $config)
    {
        return new UserRepo($config->db);
    }
}
