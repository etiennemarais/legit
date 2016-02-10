<?php
namespace App\Http\Controllers\Code;

use App\Http\Controllers\Controller;
use App\Jobs\Code\SendCodeJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Legit\Verification\Verification;
use Legit\Verification\VerificationRepository;
use Symfony\Component\HttpFoundation\Response;

class CodeController extends Controller
{
    /**
     * @param Request $request
     * @param VerificationRepository $repository
     * @return Response
     */
    public function send(Request $request, VerificationRepository $repository)
    {
        $validator = Validator::make($request->all(), Verification::$rules);

        if ($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        }

        $verification = $repository->findWithAttributes([
            'phone_number' => $request->input('phone_number'),
            'client_user_id' => $request->input('client_user_id'),
        ]);

        // Dispatch SendJob with that model and new expiry date
        $this->dispatch(new SendCodeJob($verification));

        // Return Success
        return 'Done';
    }
}
