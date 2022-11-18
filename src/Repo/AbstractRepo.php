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

    public function transactionStart()
    {
        $this->pdo->beginTransaction();
    }

    public function transactionCommit()
    {
        $this->pdo->commit();
    }

    public function transactionRollback()
    {
        $this->pdo->rollBack();
    }

    public function save(AbstractEntity $entity): void
    {
        if ($entity->getId()) {
            $this->update($entity);
        } else {
            $this->create($entity);
        }
    }

    public function create(AbstractEntity $entity): void
    {
        $data = $entity->getArrayCopy();
        unset($data[$entity::PK]);

        $sql = sprintf(
            'INSERT INTO `%s` (`%s`) VALUES (%s);',
            $entity::TABLE,
            implode('`,`', array_keys($data)),
            implode(',', array_fill(0, count($data), '?'))
        );

        $statement = $this->pdo->prepare($sql);
        $statement->execute(array_values($data));
        unset($statement);

        $entity->setId($this->pdo->lastInsertId());
    }

    /**
     * Always performs full update of the entity
     *
     * @param AbstractEntity $entity
     * @return void
     */
    public function update(AbstractEntity $entity): void
    {
        if (!$entity->getId()) {
            throw new \Exception('can not update without id for ' . get_class($entity));
        }

        $data = $entity->getArrayCopy();
        unset($data[$entity::PK]);

        $updates = [];
        foreach ($data as $name => $value) {
            $updates[] = sprintf(
                '`%s` = ?',
                $name
            );
        }

        $sql = sprintf(
            'UPDATE `%s` SET %s WHERE id = ?;',
            $entity::TABLE,
            implode(', ', $updates)
        );

        $statement = $this->pdo->prepare($sql);
        $statement->execute(array_merge(array_values($data), [$entity->getId()]));
        unset($statement);
    }

    /**
     * Always performs full update of the entity
     *
     * @param AbstractEntity $entity
     * @return void
     */
    public function delete(AbstractEntity $entity): void
    {
        $sql = sprintf(
            'DELETE FROM `%s` WHERE id = ?;',
            $entity::TABLE
        );
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$entity->getId()]);
    }

    public function find(int $id): ?AbstractEntity
    {
        $sql = sprintf(
            'SELECT * FROM `%s` WHERE id = ?;',
            $this->entity::TABLE,
        );

        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
        $data = $statement->fetch();
        if (is_array($data)) {
            return $this->createEntity($data);
        }
        return null;
    }

    public function search(array $where): array
    {
        $keys = [];
        foreach ($where as $name => $value) {
            $keys[] = sprintf(
                '`%s` = ?',
                $name
            );
        }

        $sql = sprintf(
            'SELECT * FROM :table WHERE %s;',
            implode(' AND ', $keys)
        );

        return $this->getSqlEntities($sql, array_values($where));
    }

    public function getSqlEntities($sql, $params = []): array
    {
        $sql = str_replace(':table', '`' . $this->entity::TABLE . '`', $sql);
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        $result = [];
        $data = $statement->fetchAll();
        foreach ($data as $pair) {
            $result[] = $this->createEntity($pair);
        }
        return $result;
    }

    protected function createEntity(array $data): AbstractEntity
    {
        $class = $this->entity;
        return new $class($data);
    }
}
