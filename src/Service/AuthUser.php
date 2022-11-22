<?php

namespace App\Service;

use App\Entity\UserEntity;

class AuthUser
{
    protected $user;

    public function __construct(UserEntity $user = null)
    {
        $this->user = $user;
    }

    public function isLogged()
    {
        return $this->user && $this->user->id;
    }

    public function getLoggedUserId()
    {
        return $this->user->id ?? null;
    }

    public function fromSession(string $sessionId)
    {
        $user = null;
        session_id($sessionId);
        session_start();
        if (isset($_SESSION['user'])) {
            $user = new UserEntity($_SESSION['user']);
        }
        session_write_close();

        if (!$user) {
            $user = new UserEntity();
        }

        $this->user = $user;
    }

    public function toSession(UserEntity $user): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);

        // Unset all of the session variables.
        $_SESSION = [];
        $_SESSION['user'] = $user->getArrayCopy();

        $sessionId = session_id();
        session_write_close();

        return $sessionId;
    }
}
