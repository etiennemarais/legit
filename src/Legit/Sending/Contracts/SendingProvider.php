<?php
namespace Legit\Sending\Contracts;

interface SendingProvider
{
    /**
     * @param string $phoneNumber
     * @param string $code
     * @return boolean
     */
    public function sendOTP($phoneNumber, $code);
}