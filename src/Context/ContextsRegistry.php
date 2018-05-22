<?php

namespace Hotrush\Context;

use Hotrush\Context\Entities\Request;
use Hotrush\Context\Entities\User;
use Hotrush\Context\Entities\Tags;
use Hotrush\Context\Entities\Custom;

class ContextsRegistry
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Tags
     */
    private $tags;

    /**
     * @var Custom
     */
    private $custom;

    /**
     * Contexts constructor.
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->user = new User();
        $this->tags = new Tags();
        $this->custom = new Custom();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Tags
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return Custom
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $contexts = [
            'request' => $this->request->toArray(),
        ];

        if (!$this->user->isEmpty()) {
            $contexts['user'] = $this->user->toArray();
        }

        if (!$this->tags->isEmpty()) {
            $contexts['tags'] = $this->tags->toArray();
        }

        if (!$this->custom->isEmpty()) {
            $contexts['custom'] = $this->custom->toArray();
        }

        return $contexts;
    }
}