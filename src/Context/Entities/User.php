<?php

namespace Hotrush\Context\Entities;

class User implements EntityInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @param $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param $username
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return !$this->id && !$this->username && !$this->email;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $user = [];

        if ($this->id) {
            $user['id'] = $this->id;
        }

        if ($this->username) {
            $user['username'] = $this->username;
        }

        if ($this->email) {
            $user['email'] = $this->email;
        }

        return $user;
    }
}