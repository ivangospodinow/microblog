<?php

namespace App\Repo;

use App\Entity\AbstractEntity;
use PDO;

abstract class AbstractRepo
{
    const DYNAMIC_COLUMN = '__ds_';

    protected $entity = AbstractEntity::class;
    private $pdo;
    private static $namespaceCount = 0;

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
        return $this->executeQueryWithResult($sql, $params, true);
    }

    /**
     * Example

    $this->joinEntity(
    $sql,
    UserEntity::class,
    'createdBy',
    'createdByUser'
    );

     *
     * @param string $sql
     * @param string $joinEntityClass
     * @param string $localKey
     * @param string $objectKey
     * @return void
     */
    public function joinEntity(string $sql, string $joinEntityClass, string $localKey, string $objectKey, array $props = [])
    {
        $entity = new $joinEntityClass;
        $entitiyName = end(explode('\\', $joinEntityClass));
        if (empty($props)) {
            $props = $entity->getProps();
        }

        $namespace = $this->getNamespace($entity::TABLE);

        $select = [];
        foreach ($props as $name) {
            // users_1.username AS __ds_createdByUser__ds_username
            $select[] = sprintf(
                '%s.%s AS %s%s_%s_%s',
                $namespace,
                $name,
                self::DYNAMIC_COLUMN,
                $objectKey,
                $entitiyName,
                $name
            );
        }

        // cover the basic case
        $sql = str_replace('FROM', ',' . implode(',' . PHP_EOL, $select) . PHP_EOL . 'FROM', $sql);

        // join table before where
        $join = sprintf(
            'LEFT JOIN %s AS %s ON t.%s = %s.id',
            $entity::TABLE,
            $namespace,
            $localKey,
            $namespace
        );
        $sql = str_replace(
            'WHERE',
            $join . PHP_EOL . 'WHERE',
            $sql
        );

        return $sql;
    }

    protected function createEntity(array $data, $couldHaveRelations = false): AbstractEntity
    {
        $class = $this->entity;
        $entity = new $class($data);

        if ($couldHaveRelations) {
            $this->populateDynamicJoinsToEntity($entity, $data);
        }

        return $entity;
    }

    protected function executeQueryWithResult(string $sql, array $params = [], bool $createEntity = false)
    {
        $sql = str_replace(':table', '`' . $this->entity::TABLE . '`', $sql);
        $sql = str_replace(':where', 1, $sql);

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        // an estimate, if the code should check for dynamic joins
        $couldHaveRelations = strpos($sql, self::DYNAMIC_COLUMN) !== false;

        $data = $statement->fetchAll();
        if ($createEntity) {
            $result = [];
            foreach ($data as $pair) {
                $result[] = $this->createEntity($pair, $couldHaveRelations);
            }
            return $result;
        }
        return $data;
    }

    private function populateDynamicJoinsToEntity(AbstractEntity $entity, array $data)
    {
        $relations = [];
        $checkKeyLength = strlen(self::DYNAMIC_COLUMN);
        foreach ($data as $name => $value) {
            if (substr($name, 0, $checkKeyLength) !== self::DYNAMIC_COLUMN) {
                continue;
            }

            // format:  objectKey_EntityName_field
            $pair = explode(
                '_',
                substr($name, $checkKeyLength)
            );

            if (count($pair) < 3) {
                throw new \Exception('Invalid dynamic format name: ' . $name);
            }

            $objectKey = $pair[0];
            $entityName = $pair[1];
            unset($pair[0], $pair[1]);
            // in case of underscore name
            $field = implode('_', $pair);

            if (!isset($relations[$objectKey])) {
                $relations[$objectKey] = [
                    'entity' => 'App\\Entity\\' . $entityName,
                    'props' => [],
                ];
            }

            $relations[$objectKey]['props'][$field] = $value;
        }

        foreach ($relations as $objectKey => $relation) {
            $relationEntity = new $relation['entity']($relation['props']);
            $entity->setRelation($objectKey, $relationEntity);
        }
    }

    private function getNamespace(string $prepend): string
    {
        self::$namespaceCount++;
        return $prepend . '_' . self::$namespaceCount;
    }
}
