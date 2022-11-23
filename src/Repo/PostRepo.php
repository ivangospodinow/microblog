<?php

namespace App\Repo;

use App\Entity\PostEntity;
use App\Entity\UserEntity;

class PostRepo extends AbstractRepo
{
    protected $entity = PostEntity::class;

    public function getList(array $params)
    {
        $limit = $params['list']['limit'] ?? 10;
        $offset = (($params['list']['page'] ?? 1) - 1) * $limit;
        $filter = $params['filter'] ?? [];

        $sql = <<<SQL
            SELECT
               t. *
            FROM :table AS t
            WHERE :where
            ORDER BY t.createdAt DESC
            LIMIT $limit
            OFFSET $offset
SQL;

        $params = [];

        // true or false
        if (isset($filter['featured'])) {
            $params[] = (bool) $filter['featured'];
            $sql = str_replace(
                ':where',
                ':where AND t.featured = ?',
                $sql
            );
        }

        // archive format: 2022-01
        if (isset($filter['archive'])) {
            $archiveDate = date('Y-m-01', strtotime($filter['archive']));

            $params[] = $archiveDate;
            $params[] = date('Y-m-t 23:59:59', strtotime($archiveDate));

            $sql = str_replace(
                ':where',
                ':where AND t.createdAt >= ? AND t.createdAt <= ?',
                $sql
            );
        }

        // int
        if (isset($filter['postId'])) {
            $params[] = (int) $filter['postId'];
            $sql = str_replace(
                ':where',
                ':where AND t.id = ?',
                $sql
            );
        }

        $sql = $this->joinEntity(
            $sql,
            UserEntity::class,
            'createdBy',
            'createdByUser',
            ['id', 'username']
        );

        return $this->getSqlEntities($sql, $params);
    }

    public function getMonths()
    {
        $sql = <<<SQL
            SELECT
                DATE_FORMAT(t.createdAt, "%Y-%m") AS `month`,
                COUNT(t.id) AS `count`
            FROM :table AS t
            WHERE 1
            GROUP BY `month`
            ORDER BY `month` DESC
SQL;

        return $this->executeQueryWithResult($sql);
    }
}
