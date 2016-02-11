<?php
namespace App\Jobs\Code;

use App\Jobs\Job;
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

    public function handle(SendingProvider $sendingProvider)
    {
        // Create new code with verification id link

        // Send OTP using service
        dump($sendingProvider->sendOTP($this->verification->phone_number));
        dump($this->verification);

        // Log based on status (success/error)
    }
}
