<?php
namespace Legit\Sending\Clickatell;

use Clickatell\Api\ClickatellHttp;
use Legit\Sending\Contracts\SendingProvider;

class ClickatellSendingProvider implements SendingProvider
{
    private $provider;
    const MESSAGE = 'Please verify your number by entering this OTP: %s';

    /**
     * @param string $user
     * @param string $pass
     * @param string $apiId
     */
    public function __construct($user, $pass, $apiId)
    {
        $this->provider = new ClickatellHttp($user, $pass, $apiId);
    }

    /**
     * @param string $phoneNumber
     * @param string $code
     * @return boolean
     */
    public function sendOTP($phoneNumber, $code)
    {
        $response = $this->provider->sendMessage(
            [$phoneNumber],
            sprintf(self::MESSAGE, $code)
        );

        dump($response);

        // TODO handle errors
        return true;
    }
}