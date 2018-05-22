<?php

namespace Hotrush\Events;

use Hotrush\Context\ContextsRegistry;
use \Hotrush\Helper\Timer;

/**
 *
 * Abstract Transaction class for all inheriting Transactions
 *
 * @link https://www.elastic.co/guide/en/apm/server/master/transaction-api.html
 *
 */
class Transaction extends EventBean implements \JsonSerializable
{

    /**
     * Transaction Name
     *
     * @var string
     */
    private $name;

    /**
     * Transaction Timer
     *
     * @var \Hotrush\Helper\Timer
     */
    private $timer;

    /**
     * Transaction duration
     *
     * @var float
     */
    private $duration = 0.0;

    /**
     * Create the Transaction
     *
     * @param string            $name
     * @param ContextsRegistry  $contextsRegistry
     */
    public function __construct($name, ContextsRegistry $contextsRegistry = null)
    {
        parent::__construct($contextsRegistry);
        $this->setTransactionName($name);
        $this->timer = new Timer();
    }

    /**
     * Start the Transaction
     *
     * @return void
     */
    public function start()
    {
        $this->timer->start();
    }

    /**
     * Stop the Transaction
     *
     * @return void
     */
    public function stop()
    {
        // Stop the Timer
        $this->timer->stop();

        $this->duration = round($this->timer->getDuration(), 3);
    }

    /**
     * Set the Transaction Name
     *
     * @param string $name
     *
     * @return void
     */
    public function setTransactionName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the Transaction Name
     *
     * @return string
     */
    public function getTransactionName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Serialize Transaction Event
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'timestamp' => $this->getTimestamp(),
            'name' => $this->getTransactionName(),
            'duration' => $this->getDuration(),
            'type' => $this->getMetaType(),
            'result' => $this->getMetaResult(),
            'context' => $this->getContext(),
        ];
    }

}
