<?php

namespace App\Entity;

class UserEntity extends AbstractEntity
{
    const TABLE = 'users';

    public $username;
    public $password;

}
