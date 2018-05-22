<?php

namespace Hotrush\Exception\Transaction;

/**
 * Trying to fetch an unregistered Transaction
 */
class UnknownTransactionException extends \Exception
{

    public function __construct($message = '', $code = 0, $previous = NULL)
    {
        parent::__construct(sprintf('The transaction "%s" is not registered.', $message), $code, $previous);
    }

}
