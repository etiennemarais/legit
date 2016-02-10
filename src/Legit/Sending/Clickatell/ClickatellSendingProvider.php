<?php
namespace Legit\Sending\Clickatell;

use Legit\Sending\Contracts\SendingProvider;

class ClickatellSendingProvider implements SendingProvider
{
    /**
     * @param $phoneNumber
     * @return boolean
     */
    public function sendOTP($phoneNumber)
    {
        // TODO: Implement sendOTP() method.
        return true;
    }
}