<?php

namespace App\Entity;

class PostEntity extends AbstractEntity
{
    const TABLE = 'posts';

    public $createdBy;
    public $title;
    public $content;
    public $image;
    public $featured;
    public $createdAt;
    public $updatedAt;
}
