<?php

namespace Test;

use App\Entity\PostEntity;
use App\Entity\UserEntity;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
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

    protected function getUserEntityMock()
    {
        return new UserEntity([
            'id' => rand(1, 1000),
            'username' => substr('username_' . uniqid(), 0, 20),
            'password' => 'password_' . uniqid(),
        ]);
    }

    protected function getPostEntityMock()
    {
        return new PostEntity([
            'id' => rand(1, 1000),
            'createdBy' => rand(1, 1000),
            'title' => uniqid('title-'),
            'content' => str_repeat(uniqid(), rand(1, 10)),
            'image' => '/hello.png',
            'createdAt' => date('Y-m-d H:i:s'),
            'updatedAt' => date('Y-m-d H:i:s'),
        ]);
    }
}
