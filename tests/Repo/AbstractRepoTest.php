<?php

namespace Test\Repo;

use App\Repo\AbstractRepo;
use App\Repo\UserRepo;
use PDO;
use PHPUnit\Framework\MockObject\MockObject;
use Test\AbstractTextCase;

final class AbstractRepoTest extends AbstractTextCase
{
    /**
     * @var MockObject
     */
    private $pdo;

    /**
     * @var UserRepo
     */
    private $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pdo = $this->createMock(PDO::class);
        $this->repo = $this->getMockForAbstractClass(AbstractRepo::class, [$this->pdo]);
    }

    public function testUpdateUserWithoutId()
    {
        $this->expectException(\Exception::class);

        $user = $this->getUserEntityMock();
        $user->setId(0);

        $this->repo->update($user);

    }
}
