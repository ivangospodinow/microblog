<?php

namespace Test\Repo;

use App\Entity\UserEntity;
use App\Repo\UserRepo;
use PDO;
use PDOStatement;
use PHPUnit\Framework\MockObject\MockObject;
use Test\AbstractTextCase;

final class UserRepoTest extends AbstractTextCase
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
        $this->repo = new UserRepo($this->pdo);
    }

    public function testCreate()
    {
        $user = $this->getUserEntityMock();
        $user->id = null;

        $data = $user->getArrayCopy();
        unset($data['id']);

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('execute')
            ->with(array_values($data));

        $this->pdo
            ->method('prepare')
            ->with('INSERT INTO `users` (`username`,`password`) VALUES (?,?);')
            ->willReturn($statementMock);

        $this->pdo
            ->method('lastInsertId')
            ->willReturn(31);

        $this->repo->save($user);

        $this->assertSame(31, $user->getId());
    }

    public function testUpdate()
    {
        $user = $this->getUserEntityMock();

        $data = $user->getArrayCopy();
        unset($data['id']);

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('execute')
            ->with(array_merge(array_values($data), [$user->getId()]));

        $this->pdo
            ->method('prepare')
            ->with('UPDATE `users` SET `username` = ?, `password` = ? WHERE id = ?;')
            ->willReturn($statementMock);

        $this->pdo
            ->method('lastInsertId')
            ->willReturn($user->id);

        $this->repo->save($user);

        $this->assertSame($user->id, $user->getId());
    }

    public function testFind()
    {
        $mockUser = $this->getUserEntityMock();
        $userData = $mockUser->getArrayCopy();
        $id = $userData['id'];

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('execute')
            ->with([$id]);

        $statementMock->method('fetch')
            ->willReturn($userData);

        $this->pdo
            ->method('prepare')
            ->with('SELECT * FROM `users` WHERE id = ?;')
            ->willReturn($statementMock);

        $user = $this->repo->find($id);

        $this->assertNotNull($user);
        $this->assertInstanceOf(UserEntity::class, $user);
        $this->assertSame($user->getArrayCopy(), $mockUser->getArrayCopy());
    }
}
