<?php

namespace Test\Controller;

use App\Controller\PostsApiController;
use App\Repo\PostRepo;
use App\Repo\UserRepo;
use App\Service\ServiceLocatorService;
use Test\AbstractTextCase;

final class PostsApiControllerTest extends AbstractTextCase
{
    public function testIndexBasicResponse()
    {
        $records = [$this->getPostEntityMock()];

        $postRepoMock = $this->createMock(PostRepo::class);
        $postRepoMock->method('getList')
            ->willReturn($records);

        $sm = new ServiceLocatorService((object) [
            'postRepo' => $postRepoMock,
        ]);

        $controller = new PostsApiController($sm);

        $result = $controller->index([]);
        $this->assertCount(1, $result['list']);
    }

    public function testCreateValidation()
    {
        $postRepoMock = $this->createMock(UserRepo::class);

        $sm = new ServiceLocatorService((object) [
            'postRepo' => $postRepoMock,
        ]);

        $controller = new PostsApiController($sm);

        $result = $controller->create([]);
        $this->assertFalse($result['success']);
        $this->assertSame('createdBy', $result['errors'][0]['property']);
        $this->assertSame('title', $result['errors'][1]['property']);
        $this->assertSame('content', $result['errors'][2]['property']);

        $result = $controller->create(['createdBy' => null, 'title' => null, 'content' => null]);
        $this->assertFalse($result['success']);
        $this->assertSame('createdBy', $result['errors'][0]['property']);
        $this->assertSame('title', $result['errors'][1]['property']);
        $this->assertSame('content', $result['errors'][2]['property']);

        $result = $controller->create(['createdBy' => '', 'title' => '', 'content' => '']);
        $this->assertFalse($result['success']);
        $this->assertSame('createdBy', $result['errors'][0]['property']);
        $this->assertSame('title', $result['errors'][1]['property']);

    }

    public function testCreatePost()
    {
        $postRepoMock = $this->createMock(PostRepo::class);
        $postRepoMock->method('save');

        $sm = new ServiceLocatorService((object) [
            'postRepo' => $postRepoMock,
        ]);

        $controller = new PostsApiController($sm);

        $result = $controller->create(['createdBy' => 1, 'title' => 'Title', 'content' => 'Content']);
        $this->assertTrue($result['success']);
    }

    public function testUpdateNoIdException()
    {
        $this->expectException(\Exception::class);

        $postRepoMock = $this->createMock(PostRepo::class);

        $sm = new ServiceLocatorService((object) [
            'postRepo' => $postRepoMock,
        ]);

        $controller = new PostsApiController($sm);
        $controller->update([], []);
    }

    public function testUpdate()
    {
        $post = $this->getPostEntityMock();
        $post->id = 1;

        $postRepoMock = $this->createMock(UserRepo::class);
        $postRepoMock->method('find')->with(1)->willReturn($post);
        $postRepoMock->method('save');

        $sm = new ServiceLocatorService((object) [
            'postRepo' => $postRepoMock,
        ]);

        $controller = new PostsApiController($sm);

        $result = $controller->update(['title' => 'new title'], ['id' => 1]);
        $this->assertTrue($result['success']);
    }

    public function testDeleteInvalidUser()
    {
        $postRepoMock = $this->createMock(PostRepo::class);
        $postRepoMock->method('find')->with(1)->willReturn(null);

        $sm = new ServiceLocatorService((object) [
            'postRepo' => $postRepoMock,
        ]);

        $controller = new PostsApiController($sm);

        $result = $controller->delete([], ['id' => 1]);
        $this->assertFalse($result['success']);
    }

    public function testDelete()
    {
        $post = $this->getPostEntityMock();
        $post->id = 1;

        $postRepoMock = $this->createMock(UserRepo::class);
        $postRepoMock->method('find')->with(1)->willReturn($post);
        $postRepoMock->method('delete')->with($post);

        $sm = new ServiceLocatorService((object) [
            'postRepo' => $postRepoMock,
        ]);

        $controller = new PostsApiController($sm);

        $result = $controller->delete([], ['id' => 1]);
        $this->assertTrue($result['success']);
    }

    public function tesDeleteNoIdException()
    {
        $this->expectException(\Exception::class);

        $postRepoMock = $this->createMock(PostRepo::class);

        $sm = new ServiceLocatorService((object) [
            'postRepo' => $postRepoMock,
        ]);

        $controller = new PostsApiController($sm);

        $controller->delete([], []);
    }
}
