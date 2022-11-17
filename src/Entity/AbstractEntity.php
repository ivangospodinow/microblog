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

    final public function __construct(array $props = [])
    {
        $this->exchangeArray($props);
    }

    public function getArrayCopy(): array
    {
        $data = [];
        foreach ($this->getProps() as $name) {
            $data[$name] = $this->$name;
        }
        return $data;
    }

    public function exchangeArray(array $props)
    {
        foreach ($props as $name => $value) {
            if ($this->hasProp($name)) {
                $this->$name = $value;
            } else {
                error_log('unexpected prop ' . get_class($this) . '::' . $name);
            }
        }
    }

    private function getProps(): array
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
        }
        return self::$props[$key];
    }

    private function hasProp(string $key): bool
    {
        return in_array($key, $this->getProps());
    }

}
