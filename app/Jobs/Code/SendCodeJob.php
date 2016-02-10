<?php
namespace App\Jobs\Code;

use App\Jobs\Job;
use Legit\Sending\Contracts\SendingProvider;

class SendCodeJob extends Job
{
    // Create new code with verification id link

    // Send OTP using service
    // Update verification model to be "awaiting verification"

    // Log based on status (success/error)
    public function handle(SendingProvider $sendingProvider)
    {
        dump("Job Dispatched");
    }
}
