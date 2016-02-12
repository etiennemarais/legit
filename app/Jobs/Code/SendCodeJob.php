<?php
namespace App\Jobs\Code;

use App\Jobs\Job;
use Illuminate\Support\Facades\Log;
use Legit\Code\CodeRepository;
use Legit\Sending\Clickatell\exceptions\ClickatellSendingException;
use Legit\Sending\Contracts\SendingProvider;
use Legit\Verification\Verification;

class SendCodeJob extends Job
{
    private $verification;

    /**
     * @param Verification $verification
     */
    public function __construct(Verification $verification)
    {
        $this->verification = $verification;
    }

    /**
     * @param SendingProvider $sendingProvider
     * @param CodeRepository $codeRepository
     */
    public function handle(SendingProvider $sendingProvider, CodeRepository $codeRepository)
    {
        $codeObject = $codeRepository->createWithAttributes([
            'country_id' => (int)$this->verification->country_id,
            'verification_id' => (int)$this->verification->id,
        ]);

        try {
            $sendingProvider->sendOTP($this->verification->phone_number, $codeObject->code);
        } catch (ClickatellSendingException $e) {

            $this->handleException($sendingProvider, $e);
        }
    }

    public function failed()
    {
        Log::error(SendCodeJob::class . " failed: " . $this->verification);
    }

    /**
     * @param SendingProvider $sendingProvider
     * @param ClickatellSendingException $e
     * @throws ClickatellSendingException
     */
    private function handleException(SendingProvider $sendingProvider, ClickatellSendingException $e)
    {
        if (in_array($e->getCode(), $sendingProvider->getRetryQueueCodes())) {
            // QUEUE_TIMEOUT is in minutes
            $this->release(env('QUEUE_RETRY_TIMEOUT') * 60);
            return;
        }

        // If not in retry queue then fail hard
        throw $e;
    }
}
