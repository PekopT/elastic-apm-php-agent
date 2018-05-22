<?php

namespace Hotrush\Exception;

/**
 * Application Tear Up has missing App Name in Config
 */
class MissingAppNameException extends \Exception
{

    public function __construct($message = '', $code = 0, $previous = NULL)
    {
        parent::__construct(sprintf('No app name registered in agent config.', $message), $code, $previous);
    }

}
