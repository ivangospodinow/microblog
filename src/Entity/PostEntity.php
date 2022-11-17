<?php

namespace App\Entity;

class PostEntity extends AbstractEntity
{
    const TABLE = 'posts';

    public $userId;
    public $uri;
    public $title;
    public $content;
    public $image;
    public $createdAt;
    public $updatedAt;
}
