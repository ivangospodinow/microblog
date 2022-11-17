<?php

namespace App\Repo;

use App\Entity\AbstractEntity;
use PDO;

abstract class AbstractRepo
{
    protected $entity = AbstractEntity::class;
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(AbstractEntity $entity)
    {

    }

    public function create(AbstractEntity $entity)
    {

    }

    public function update(AbstractEntity $entity)
    {

    }

    public function find(int $pk): ?AbstractEntity
    {
        return null;
    }
}
