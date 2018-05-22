<?php

namespace Hotrush\Stores;

use \Hotrush\Events\Transaction;
use \Hotrush\Exception\Transaction\DuplicateTransactionNameException;

/**
 *
 * Store for the Transaction Events
 *
 */
class TransactionsStore extends Store
{

    /**
     * Register a Transaction
     *
     * @throws \Hotrush\Exception\Transaction\DuplicateTransactionNameException
     *
     * @param \Hotrush\Events\Transaction $transaction
     *
     * @return void
     */
    public function register(Transaction $transaction)
    {
        $name = $transaction->getTransactionName();

        // Do not override the
        if (isset($this->store[$name])) {
            throw new DuplicateTransactionNameException($name);
        }

        // Push to Store
        $this->store[$name] = $transaction;
    }

    /**
     * Fetch a Transaction from the Store
     *
     * @param final string $name
     *
     * @return mixed: \Hotrush\Events\Transaction | null
     */
    public function fetch($name)
    {
        return $this->store[$name] ? $this->store[$name] : null;
    }

    /**
     * Serialize the Transactions Events Store
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_values($this->store);
    }

}
