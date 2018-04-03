<?php

namespace PhilKra\Events;

use PhilKra\Events\Context\Custom;
use PhilKra\Events\Context\Request;
use PhilKra\Events\Context\Tags;
use PhilKra\Events\Context\User;
use \Ramsey\Uuid\Uuid;

/**
 *
 * EventBean for occuring events such as Excpetions or Transactions
 *
 */
class EventBean
{
    /**
     * UUID
     *
     * @var string
     */
    private $id;

    /**
     * Error occurred on Timestamp
     *
     * @var string
     */
    private $timestamp;

    /**
     * Event Metadata
     *
     * @var array
     */
    private $meta = [
        'result' => 200,
        'type' => 'generic'
    ];

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
     * Init the Event with the Timestamp and UUID
     *
     * @link https://github.com/philkra/elastic-apm-php-agent/issues/3
     *
     * @param array $contexts
     */
    public function __construct(array $contexts)
    {
        // Generate Random UUID
        $this->id = Uuid::uuid4()->toString();

        $this->request = new Request();
        $this->user = new User();
        $this->tags = new Tags();
        $this->custom = new Custom();

        // Get UTC timestamp of Now
        $timestamp = \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)));
        $timestamp->setTimeZone(new \DateTimeZone('UTC'));
        $this->timestamp = $timestamp->format('Y-m-d\TH:i:s.u\Z');
    }

    /**
     * Get the Event Id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get the Event's Timestamp
     *
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * Set the Transaction Meta data
     *
     * @param array $meta
     *
     * @return void
     */
    public final function setMeta(array $meta)
    {
        $this->meta = array_merge($this->meta, $meta);
    }

    /**
     * Get Type defined in Meta
     *
     * @return string
     */
    protected final function getMetaType(): string
    {
        return $this->meta['type'];
    }

    /**
     * Get the Result of the Event from the Meta store
     *
     * @return string
     */
    protected final function getMetaResult(): string
    {
        return (string)$this->meta['result'];
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Tags
     */
    public function getTags(): Tags
    {
        return $this->tags;
    }

    /**
     * @return Custom
     */
    public function getCustom(): Custom
    {
        return $this->custom;
    }

    /**
     * Get the Events Context
     *
     * @link https://www.elastic.co/guide/en/apm/server/current/transaction-api.html#transaction-context-schema
     *
     * @return array
     */
    protected final function getContext(): array
    {
        $context = [
            'request' => $this->request->toArray(),
        ];

        if (!$this->user->isEmpty()) {
            $context['user'] = $this->user->toArray();
        }

        if (!$this->tags->isEmpty()) {
            $context['tags'] = $this->tags->toArray();
        }

        if (!$this->custom->isEmpty()) {
            $context['custom'] = $this->custom->toArray();
        }

        return $context;
    }

}
