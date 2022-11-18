<?php

namespace Test\Controller;

use App\Controller\LoginApiController;
use App\Repo\UserRepo;
use App\Service\ServiceLocatorService;
use Test\AbstractTextCase;

final class LoginApiControllerTest extends AbstractTextCase
{
    public function testLoginWithoutData()
    {
        $userRepoMock = $this->createMock(UserRepo::class);

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new LoginApiController($sm);

        $result = $controller->login([]);
        $this->assertFalse($result['success']);
        $this->assertSame('username', $result['errors'][0]['property']);
        $this->assertSame('password', $result['errors'][1]['property']);

        $result = $controller->login(['username' => null, 'password' => null]);
        $this->assertFalse($result['success']);
        $this->assertSame('username', $result['errors'][0]['property']);
        $this->assertSame('password', $result['errors'][1]['property']);

        $result = $controller->login(['username' => '', 'password' => '']);
        $this->assertFalse($result['success']);
        $this->assertSame('username', $result['errors'][0]['property']);
        $this->assertSame('password', $result['errors'][1]['property']);
    }

    public function testLoginWrongUsername()
    {
        $userRepoMock = $this->createMock(UserRepo::class);
        $userRepoMock->method('getByUserName')->willReturn(null);

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new LoginApiController($sm);

        $result = $controller->login(['username' => 'admin', 'password' => 'admin']);
        $this->assertFalse($result['success']);
        $this->assertSame('username', $result['errors'][0]['property']);
    }

    public function testLoginWrongPassword()
    {
        $user = $this->getUserEntityMock();
        $user->username = 'admin';
        $user->password = 'some false password';

        $userRepoMock = $this->createMock(UserRepo::class);
        $userRepoMock->method('getByUserName')->willReturn($user);

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new LoginApiController($sm);

        $result = $controller->login(['username' => 'admin', 'password' => 'admin']);
        $this->assertFalse($result['success']);
        $this->assertSame('username', $result['errors'][0]['property']);
    }

    public function testLogin()
    {
        $user = $this->getUserEntityMock();
        $user->username = 'admin';
        $user->password = '$2y$10$PLa.kZr5CoMRqOv2on2ZX.Hq2zJxyhfw.bDtYDPoUf.QcHnFzTNCO';

        $userRepoMock = $this->createMock(UserRepo::class);
        $userRepoMock->method('getByUserName')->with($user->username)->willReturn($user);

        $sm = new ServiceLocatorService((object) [
            'userRepo' => $userRepoMock,
        ]);

        $controller = new LoginApiController($sm);

        $result = $controller->login(['username' => 'admin', 'password' => 'admin']);
        $this->assertTrue($result['success']);

        $data = $user->getArrayCopy();
        unset($data['password']);
        $this->assertSame($data, $result['data']);
    }
}
