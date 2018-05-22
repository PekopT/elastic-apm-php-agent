<?php

namespace Hotrush;

use Hotrush\Context\ContextsRegistry;
use Hotrush\Stores\ErrorsStore;
use Hotrush\Stores\TransactionsStore;
use Hotrush\Events\Transaction;
use Hotrush\Events\Error;
use Hotrush\Helper\Timer;
use Hotrush\Helper\Config;
use Hotrush\Middleware\Connector;
use Hotrush\Exception\Transaction\UnknownTransactionException;

/**
 *
 * APM Agent
 *
 * @link https://www.elastic.co/guide/en/apm/server/master/transaction-api.html
 *
 */
class Agent
{

    /**
     * Agent Version
     *
     * @var string
     */
    const VERSION = '0.2.0';

    /**
     * Agent Name
     *
     * @var string
     */
    const NAME = 'elastic-php';

    /**
     * Config Store
     *
     * @var \Hotrush\Helper\Config
     */
    private $config;

    /**
     * Transactions Store
     *
     * @var \Hotrush\Stores\TransactionsStore
     */
    private $transactionsStore;

    /**
     * Error Events Store
     *
     * @var \Hotrush\Stores\ErrorsStore
     */
    private $errorsStore;

    /**
     * Apm Timer
     *
     * @var \Hotrush\Helper\Timer
     */
    private $timer;

    /**
     * Common/Shared Contexts for Errors and Transactions
     *
     * @var ContextsRegistry
     */
    private $sharedContextRegistry;

    /**
     * Setup the APM Agent
     *
     * @param array             $config
     * @param ContextsRegistry  $sharedContextRegistry Set shared contexts such as user and tags
     */
    public function __construct(array $config, ContextsRegistry $sharedContextRegistry = null)
    {
        // Init Agent Config
        $this->config = new Config($config);

        // Init the Shared Context
        if ($sharedContextRegistry) {
            $this->sharedContextRegistry = $sharedContextRegistry;
        }

        // Initialize Event Stores
        $this->transactionsStore = new TransactionsStore();
        $this->errorsStore = new ErrorsStore();

        // Start Global Agent Timer
        $this->timer = new Timer();
        $this->timer->start();
    }

    /**
     * Start the Transaction capturing
     *
     * @throws \Hotrush\Exception\Transaction\DuplicateTransactionNameException
     *
     * @param string $name
     *
     * @return void
     */
    public function startTransaction($name)
    {
        // Create and Store Transaction
        $this->transactionsStore->register(new Transaction($name, $this->sharedContextRegistry));

        // Start the Transaction
        $this->transactionsStore->fetch($name)->start();
    }

    /**
     * Stop the Transaction
     *
     * @throws \Hotrush\Exception\Transaction\UnknownTransactionException
     *
     * @param string $name
     * @param array $meta , Def: []
     *
     * @return void
     */
    public function stopTransaction($name, array $meta = [])
    {
        $this->getTransaction($name)->stop();
        $this->getTransaction($name)->setMeta($meta);
    }

    /**
     * Get a Transaction
     *
     * @throws \Hotrush\Exception\Transaction\UnknownTransactionException
     * @param string $name
     * @return mixed \Hotrush\Events\Transaction|null
     */
    public function getTransaction($name)
    {
        $transaction = $this->transactionsStore->fetch($name);
        if ($transaction === null) {
            throw new UnknownTransactionException($name);
        }

        return $transaction;
    }

    /**
     * Register a Thrown Exception, Error, etc.
     *
     * @link http://php.net/manual/en/class.throwable.php
     * @param \Throwable $thrown
     * @return void
     */
    public function captureThrowable($thrown)
    {
        $this->errorsStore->register(new Error($thrown, $this->sharedContextRegistry));
    }

    /**
     * Get the Agent Config
     *
     * @return \Hotrush\Helper\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Send Data to APM Service
     *
     * @return bool
     */
    public function send()
    {
        // Is the Agent enabled ?
        if ($this->config->get('active') === false) {
            return false;
        }

        $connector = new Connector($this->config);
        $status = true;

        // Commit the Errors
        if ($this->errorsStore->isEmpty() === false) {
            $status = $status && $connector->sendErrors($this->errorsStore);
        }

        // Commit the Transactions
        if ($this->transactionsStore->isEmpty() === false) {
            $status = $status && $connector->sendTransactions($this->transactionsStore);
        }

        return $status;
    }

}
