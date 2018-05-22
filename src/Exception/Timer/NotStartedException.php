<?php

namespace Hotrush\Exception\Timer;

/**
 * Trying to stop a Timer that has not been started
 */
class NotStartedException extends \Exception
{

    public function __construct($message = '', $code = 0, $previous = NULL)
    {
        parent::__construct('Can\'t stop a timer which isn\'t started.', $code, $previous);
    }

}
