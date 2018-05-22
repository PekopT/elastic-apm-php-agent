<?php

namespace Hotrush\Context\Entities;

class Tags implements EntityInterface
{
    /**
     * @var array
     */
    private $tags = [];

    /**
     * @param $key
     * @param $value
     */
    public function addTag($key, $value)
    {
        $this->tags[$key] = (string) $value;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }

    public function isEmpty()
    {
        return empty($this->tags);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->tags;
    }
}