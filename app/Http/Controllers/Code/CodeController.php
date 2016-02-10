<?php
namespace App\Http\Controllers\Code;

use App\Http\Controllers\Controller;
use App\Jobs\Code\SendCodeJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Legit\Verification\Verification;
use Symfony\Component\HttpFoundation\Response;

class CodeController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), Verification::$rules);

        if ($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        }

        $phoneNumber = $request->input('phone_number');
        $clientUserId = $request->input('client_user_id');

        // Get Verification object for phone_number+client

        $verification = new Verification();

        // Dispatch SendJob with that model and new expiry date
        $this->dispatchFrom(SendCodeJob::class, $verification);

        // Return Success
        return 'Done';
    }
}
