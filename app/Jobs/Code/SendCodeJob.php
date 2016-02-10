<?php
namespace App\Jobs\Code;

use App\Jobs\Job;
use Legit\Sending\Contracts\SendingProvider;
use Legit\Verification\Verification;

class SendCodeJob extends Job
{
    private $verification;

    public function __construct(Verification $verification)
    {
        $this->verification = $verification;
    }
    // Create new code with verification id link

    // Send OTP using service
    // Update verification model to be "awaiting verification"

    // Log based on status (success/error)
    // /*SendingProvider $sendingProvider*/
    public function handle(SendingProvider $sendingProvider)
    {
        dump($this->verification);
    }
}
