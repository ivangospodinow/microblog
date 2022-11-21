<?php

namespace App\Entity;

abstract class AbstractEntity
{
    const TABLE = '';
    const PK = 'id';

    public $id;

    /**
     * __CLASS__ => [props]
     *
     * @var array
     */
    private static $props = [];
    private $relations = [];

    final public function __construct(array $props = [])
    {
        $this->exchangeArray($props);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getArrayCopy(): array
    {
        $data = [];
        foreach ($this->getProps() as $name) {
            $data[$name] = $this->$name;
        }

        foreach ($this->relations as $key => $entity) {
            if (!isset($data[$key])) {
                $data[$key] = $entity->getArrayCopy();
            } else {
                throw new \Exception('Relation key can not colide with internal props. ' . $key);
            }
        }

        return $data;
    }

    public function exchangeArray(array $props)
    {
        if (isset($props[$this::PK])) {
            $props[$this::PK] = (int) $props[$this::PK];
        }

        foreach ($props as $name => $value) {
            if ($this->hasProp($name)) {
                $this->$name = $value;
            }
        }
    }

    public function setRelation(string $key, AbstractEntity $entity): self
    {
        $this->relations[$key] = $entity;
        return $this;
    }

    public function getProps(): array
    {
        $key = get_class($this);
        if (!isset(self::$props[$key])) {
            // we want id as first key
            self::$props[$key] = array_unique(
                array_merge(
                    [self::PK],
                    array_keys(get_object_vars($this))
                )
            );
            unset(self::$props[$key][array_search('relations', self::$props[$key])]);
        }
        return self::$props[$key];
    }

    private function hasProp(string $key): bool
    {
        return in_array($key, $this->getProps());
    }

}
