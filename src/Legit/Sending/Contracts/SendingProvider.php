<?php
namespace Legit\Sending\Contracts;

interface SendingProvider
{
    /**
     * @param $phoneNumber
     * @return boolean
     */
    public function sendOTP($phoneNumber);
}