<?php

namespace Hotrush\Context\Entities;

class Custom implements EntityInterface
{
    /**
     * @var array
     */
    private $custom = [];

    /**
     * @param $key
     * @param $value
     */
    public function addCustom($key, $value)
    {
        $this->custom[$key] = $value;
    }

    /**
     * @param array $custom
     */
    public function setCustom(array $custom)
    {
        $this->custom = $custom;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->custom);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->custom;
    }
}