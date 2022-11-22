<?php

namespace App\Factory;

use App\Service\ImageStoreService;
use App\Service\ServiceLocatorService;

class ImageStoreServiceFactory extends AbstractFactory
{
    public function __invoke(ServiceLocatorService $serviceLocator)
    {
        $config = $serviceLocator->getConfig();
        return new ImageStoreService($config['settings']['imagesDir'], $config['settings']['imagesPublicPath']);
    }
}
