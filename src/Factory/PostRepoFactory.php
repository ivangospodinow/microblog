<?php

namespace App\Factory;

use App\Repo\PostRepo;
use App\Service\ServiceLocatorService;

class PostRepoFactory extends AbstractFactory
{
    public function __invoke(ServiceLocatorService $serviceLocator)
    {
        return new PostRepo($serviceLocator->get('db'));
    }
}
