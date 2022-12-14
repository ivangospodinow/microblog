<?php

namespace Test\Entity;

use App\Entity\AbstractEntity;
use Test\AbstractTestCase;

final class AbstractEntityTest extends AbstractTestCase
{
    /**
     * @var AbstractEntity
     */
    private $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = $this->getMockForAbstractClass(AbstractEntity::class);
    }

    public function testConstructor()
    {
        $this->mock = $this->getMockForAbstractClass(AbstractEntity::class, [['id' => 5]]);
        $this->assertSame(['id' => 5], $this->mock->getArrayCopy());
    }

    public function testEmptyConstructor()
    {
        $this->assertSame(['id' => null], $this->mock->getArrayCopy());
    }

    public function testProps(): void
    {
        $this->assertTrue($this->invokeMethod($this->mock, 'hasProp', [AbstractEntity::PK]));
        $this->assertSame(['id'], $this->invokeMethod($this->mock, 'getProps'));
    }

    public function testSetAndGetProps(): void
    {
        $this->assertSame(['id' => null], $this->mock->getArrayCopy());

        $this->mock->exchangeArray(['id' => 1, 'undefined' => true]);

        $this->assertSame(['id' => 1], $this->mock->getArrayCopy());
    }

    public function testGetIdNull(): void
    {
        $this->assertNull($this->mock->getId());
    }

    public function testGetIdSet(): void
    {
        $this->mock = $this->getMockForAbstractClass(AbstractEntity::class, [['id' => 5]]);
        $this->assertSame(5, $this->mock->getId());
    }

    public function tesSetId(): void
    {
        $this->mock->setId(2);
        $this->assertSame(2, $this->mock->getId());
    }
}
