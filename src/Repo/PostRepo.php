<?php

namespace App\Repo;

use App\Entity\PostEntity;

class PostRepo extends AbstractRepo
{
    protected $entity = PostEntity::class;

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
}
