<?php

namespace Test;

use App\Entity\UserEntity;
use PHPUnit\Framework\TestCase;

abstract class AbstractTextCase extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function invokeMethod($object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    protected function getUserEntityMock(): UserEntity
    {
        return new UserEntity([
            'id' => rand(1, 1000),
            'username' => 'username_' . uniqid(),
            'password' => 'password_' . uniqid(),
        ]);
    }
}
