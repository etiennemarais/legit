<?php
namespace App\Jobs\Code;

use App\Jobs\Job;
use Illuminate\Support\Facades\Log;
use Legit\Code\CodeRepository;
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

        // Send OTP using service
        $sendingProvider->sendOTP($this->verification->phone_number, $codeObject->code);

        // Log based on status (success/error)



        // retry queue
        //$this->release(env('QUEUE_RETRY_TIMEOUT'));
    }

    public function failed()
    {
        Log::error("Job Failed HERE again");
    }
}
