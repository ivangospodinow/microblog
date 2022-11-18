<?php

namespace Test\Entity;

use App\Entity\UserEntity;
use Test\AbstractTextCase;

final class UserEntityTest extends AbstractTextCase
{
    private $props = ['id' => 1, 'username' => 'admin', 'password' => 'admin'];

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testConstructor()
    {
        $mock = new UserEntity($this->props);
        $this->assertSame($this->props, $mock->getArrayCopy());

        foreach ($this->props as $name => $value) {
            $this->assertSame($value, $mock->$name);
        }
    }
}
