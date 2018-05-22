<?php

namespace Hotrush\Stores;

/**
 *
 * Registry for captured the Events
 *
 */
class Store implements \JsonSerializable
{

    /**
     * Set of Events
     *
     * @var array \Hotrush\Events\EventBean[]
     */
    protected $store = [];

    /**
     * Get all Registered Errors
     *
     * @return array \Hotrush\Events\EventBean[]
     */
    public function list2()
    {
        return $this->store;
    }

    /**
     * Is the Store Empty ?
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->store);
    }

    /**
     * Empty the Store
     *
     * @return void
     */
    public function reset()
    {
        $this->store = [];
    }

    /**
     * Serialize the Events Store
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->store;
    }

}
