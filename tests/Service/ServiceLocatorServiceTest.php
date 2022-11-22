<?php

namespace Test\Service;

use App\Service\ServiceLocatorService;
use Test\AbstractTestCase;

final class ServiceLocatorServiceTest extends AbstractTestCase
{
    public function testMethods()
    {
        $config = (object) array(
            'test' => 1,
        );
        $service = new ServiceLocatorService($config);

        $this->assertSame($config, $service->getConfig());
        $this->assertSame(1, $service->get('test'));
        $this->assertTrue($service->has('test'));
        $this->assertFalse($service->has('undefined_key'));
    }
}
