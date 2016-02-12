<?php
namespace Legit\Sending\Clickatell;

use Clickatell\Api\ClickatellHttp;
use Legit\Sending\Clickatell\exceptions\ClickatellSendingException;
use Legit\Sending\Contracts\SendingProvider;

class ClickatellSendingProvider implements SendingProvider
{
    private $provider;
    const MESSAGE = '%s is your code for OLX. Please enter it to verify your phone number :)';
    const SUCCESSFUL_SEND = 0;
    const AUTH_FAILED = 1;
    const INVALID_DEST_ADDRESS = 105;
    const INVALID_API_ID = 108;
    const CANNOT_ROUTE_MESSAGE = 114;
    const DEST_MOBILE_BLOCKED = 121;
    const DEST_MOBILE_OPTED_OUT = 122;
    const MAX_MT_EXCEEDED = 130;
    const NO_CREDIT_LEFT = 301;
    const INTERNAL_ERROR = 901;

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

        $this->handleProviderResponses($response);
    }

    /**
     * @return array
     */
    public function getFailedQueueCodes()
    {
        return [
            self::AUTH_FAILED,
            self::INVALID_DEST_ADDRESS,
            self::INVALID_API_ID,
            self::CANNOT_ROUTE_MESSAGE,
            self::DEST_MOBILE_BLOCKED,
            self::DEST_MOBILE_OPTED_OUT,
            self::MAX_MT_EXCEEDED,
        ];
    }

    /**
     * @return array
     */
    public function getRetryQueueCodes()
    {
        return [
            self::NO_CREDIT_LEFT,
            self::INTERNAL_ERROR,
        ];
    }

    /**
     * @param array $response
     * @throws ClickatellSendingException
     */
    protected function handleProviderResponses(array $response)
    {
        foreach ($response as $messageResponse) {
            $errorCode = (int)$messageResponse->errorCode;
            switch ($errorCode) {
                default:
                    $this->throwClickatellException($messageResponse, $errorCode);
                    break;
                case self::SUCCESSFUL_SEND:
                    // Do nothing, job has finished successfully :)
                    break;
            }
        }
    }

    /**
     * @param object $messageResponse
     * @param integer $errorCode
     * @throws ClickatellSendingException
     */
    protected function throwClickatellException($messageResponse, $errorCode)
    {
        throw new ClickatellSendingException((string)$messageResponse->error, $errorCode);
    }
}
