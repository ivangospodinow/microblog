<?php

namespace App\Factory;

use App\Repo\UserRepo;
use App\Service\ServiceLocatorService;

class UserRepoFactory extends AbstractFactory
{
    public function __invoke(ServiceLocatorService $serviceLocator)
    {
        return new UserRepo($serviceLocator->get('db'));
    }
}
