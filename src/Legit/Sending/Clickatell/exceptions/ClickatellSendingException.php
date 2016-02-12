<?php
namespace Legit\Sending\Clickatell\exceptions;

use Exception;

class ClickatellSendingException extends Exception
{
    /**
     * ClickatellSendingException constructor.
     * @param string $message
     * @param integer $code
     */
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}
