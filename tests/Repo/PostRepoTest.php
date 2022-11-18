<?php

namespace Test\Repo;

use App\Entity\PostEntity;
use App\Repo\PostRepo;
use App\Repo\UserRepo;
use PDO;
use PDOStatement;
use PHPUnit\Framework\MockObject\MockObject;
use Test\AbstractTextCase;

final class PostRepoTest extends AbstractTextCase
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
        $this->repo = new PostRepo($this->pdo);
    }

    public function testCreate()
    {
        $post = $this->getPostEntityMock();
        $post->id = null;

        $data = $post->getArrayCopy();
        unset($data['id']);

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('execute')
            ->with(array_values($data));

        $this->pdo
            ->method('prepare')
            ->with('INSERT INTO `posts` (`userId`,`uri`,`title`,`content`,`image`,`createdAt`,`updatedAt`) VALUES (?,?,?,?,?,?,?);')
            ->willReturn($statementMock);

        $this->pdo
            ->method('lastInsertId')
            ->willReturn(31);

        $this->repo->save($post);

        $this->assertSame(31, $post->getId());
    }

    public function testUpdate()
    {
        $post = $this->getPostEntityMock();

        $data = $post->getArrayCopy();
        unset($data['id']);

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('execute')
            ->with(array_merge(array_values($data), [$post->getId()]));

        $this->pdo
            ->method('prepare')
            ->with('UPDATE `posts` SET `userId` = ?, `uri` = ?, `title` = ?, `content` = ?, `image` = ?, `createdAt` = ?, `updatedAt` = ? WHERE id = ?;')
            ->willReturn($statementMock);

        $this->pdo
            ->method('lastInsertId')
            ->willReturn($post->id);

        $this->repo->save($post);

        $this->assertSame($post->id, $post->getId());
    }

    public function testFind()
    {
        $mockPost = $this->getPostEntityMock();
        $postData = $mockPost->getArrayCopy();
        $id = $postData['id'];

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('execute')
            ->with([$id]);

        $statementMock->method('fetch')
            ->willReturn($postData);

        $this->pdo
            ->method('prepare')
            ->with('SELECT * FROM `posts` WHERE id = ?;')
            ->willReturn($statementMock);

        $post = $this->repo->find($id);

        $this->assertNotNull($post);
        $this->assertInstanceOf(PostEntity::class, $post);
        $this->assertSame($post->getArrayCopy(), $mockPost->getArrayCopy());
    }

    public function testDelete()
    {
        $post = $this->getPostEntityMock();

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('execute')
            ->with([$post->getId()]);

        $this->pdo
            ->method('prepare')
            ->with('DELETE FROM `posts` WHERE id = ?;')
            ->willReturn($statementMock);

        $result = $this->repo->delete($post);

        $this->assertNull($result);
    }
}
