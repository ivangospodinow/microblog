<?php

namespace App\Repo;

use App\Entity\UserEntity;

class UserRepo extends AbstractRepo
{
    protected $entity = UserEntity::class;

    public function getList($limit = 10, $offset = 0)
    {
        $sql = <<<SQL
            SELECT
                *
            FROM :table
            WHERE 1
            ORDER BY id DESC
            LIMIT $limit
            OFFSET $offset
SQL;

        return $this->getSqlEntities($sql);
    }

    public function getByUserName(string $username): ?UserEntity
    {
        return $this->search(['username' => $username])[0] ?? null;
    }
}
