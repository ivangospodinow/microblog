<?php

namespace Test\Controller;

use App\Controller\UsersApiController;
use App\Repo\UserRepo;
use App\Service\ServiceLocatorService;
use Test\AbstractTextCase;

final class UsersApiControllerTest extends AbstractTextCase
{
    public function testIndexBasicResponse()
    {
        $records = [$this->getUserEntityMock()];

        $userRepoMock = $this->createMock(UserRepo::class);
        $userRepoMock->method('getList')
            ->willReturn($records);

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new UsersApiController($sm);

        $result = $controller->index([]);

        $this->assertCount(1, $result['list']);
        $this->assertArrayNotHasKey('password', $result['list'][0]);
    }

    public function testCreateValidation()
    {
        $userRepoMock = $this->createMock(UserRepo::class);

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new UsersApiController($sm);

        $result = $controller->create([]);
        $this->assertFalse($result['success']);
        $this->assertSame('username', $result['errors'][0]['property']);
        $this->assertSame('password', $result['errors'][1]['property']);

        $result = $controller->create(['username' => null, 'password' => null]);
        $this->assertFalse($result['success']);
        $this->assertSame('username', $result['errors'][0]['property']);
        $this->assertSame('password', $result['errors'][1]['property']);

        $result = $controller->create(['username' => '', 'password' => '']);
        $this->assertFalse($result['success']);
        $this->assertSame('username', $result['errors'][0]['property']);
        $this->assertSame('password', $result['errors'][1]['property']);
    }

    public function testCreateValidationUserExists()
    {
        $userRepoMock = $this->createMock(UserRepo::class);
        $userRepoMock->method('transactionStart');
        $userRepoMock->method('getByUserName')->with('admin')->willReturn($this->getUserEntityMock());

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new UsersApiController($sm);

        $result = $controller->create(['username' => 'admin', 'password' => 'admin']);
        $this->assertFalse($result['success']);
        $this->assertSame('username', $result['errors'][0]['property']);

    }

    public function testCreateUser()
    {
        $userRepoMock = $this->createMock(UserRepo::class);
        $userRepoMock->method('transactionStart');
        $userRepoMock->method('getByUserName')->with('admin')->willReturn(null);
        $userRepoMock->method('save');
        $userRepoMock->method('transactionCommit');

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new UsersApiController($sm);

        $result = $controller->create(['username' => 'admin', 'password' => 'admin']);
        $this->assertTrue($result['success']);
    }

    public function testUpdateNoIdException()
    {
        $this->expectException(\Exception::class);

        $userRepoMock = $this->createMock(UserRepo::class);

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new UsersApiController($sm);

        $controller->update([], []);
    }

    public function testUpdateValidation()
    {
        $userRepoMock = $this->createMock(UserRepo::class);

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new UsersApiController($sm);

        $result = $controller->update([], ['id' => 1]);
        $this->assertFalse($result['success']);
        $this->assertSame('username', $result['errors'][0]['property']);

        $result = $controller->update(['username' => null, 'password' => null], ['id' => 1]);
        $this->assertFalse($result['success']);
        $this->assertSame('username', $result['errors'][0]['property']);
        $this->assertSame('password', $result['errors'][1]['property']);

        $result = $controller->update(['username' => '', 'password' => ''], ['id' => 1]);
        $this->assertFalse($result['success']);
        $this->assertSame('username', $result['errors'][0]['property']);
        $this->assertSame('password', $result['errors'][1]['property']);
    }

    public function testUpdateValidationUserExists()
    {
        $userRepoMock = $this->createMock(UserRepo::class);
        $userRepoMock->method('transactionStart');
        $userRepoMock->method('find')->with(1)->willReturn(null);

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new UsersApiController($sm);

        $result = $controller->update(['username' => 'admin'], ['id' => 1]);
        $this->assertFalse($result['success']);
        $this->assertSame('id', $result['errors'][0]['property']);

    }

    public function testUpdateValidatioUsernameExists()
    {
        $user = $this->getUserEntityMock();
        $user->id = 1;

        $userCheck = clone $user;
        $userCheck->id = $user->id + 1;

        $userRepoMock = $this->createMock(UserRepo::class);
        $userRepoMock->method('transactionStart');
        $userRepoMock->method('find')->with(1)->willReturn($user);
        $userRepoMock->method('getByUserName')->with($user->username)->willReturn($userCheck);

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new UsersApiController($sm);

        $result = $controller->update(['username' => $user->username], ['id' => 1]);

        $this->assertFalse($result['success']);
        $this->assertSame('username', $result['errors'][0]['property']);
    }

    public function testUpdate()
    {
        $user = $this->getUserEntityMock();
        $user->id = 1;

        $userRepoMock = $this->createMock(UserRepo::class);
        $userRepoMock->method('transactionStart');
        $userRepoMock->method('find')->with(1)->willReturn($user);
        $userRepoMock->method('getByUserName')->with($user->username)->willReturn(null);
        $userRepoMock->method('save');
        $userRepoMock->method('transactionCommit');

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new UsersApiController($sm);

        $result = $controller->update(['username' => $user->username], ['id' => 1]);
        $this->assertTrue($result['success']);
    }

    public function testDeleteInvalidUser()
    {
        $userRepoMock = $this->createMock(UserRepo::class);
        $userRepoMock->method('find')->with(1)->willReturn(null);

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new UsersApiController($sm);

        $result = $controller->delete([], ['id' => 1]);
        $this->assertFalse($result['success']);
    }

    public function testDelete()
    {
        $user = $this->getUserEntityMock();
        $user->id = 1;

        $userRepoMock = $this->createMock(UserRepo::class);
        $userRepoMock->method('find')->with(1)->willReturn($user);
        $userRepoMock->method('delete')->with($user);

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new UsersApiController($sm);

        $result = $controller->delete([], ['id' => 1]);
        $this->assertTrue($result['success']);
    }

    public function tesDeleteNoIdException()
    {
        $this->expectException(\Exception::class);

        $userRepoMock = $this->createMock(UserRepo::class);

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new UsersApiController($sm);

        $controller->delete([], []);
    }
}
