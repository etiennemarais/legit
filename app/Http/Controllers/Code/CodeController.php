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

        $repository->setAwaitingVerificationStatus($verification);

        $this->dispatch(new SendCodeJob($verification));

        return $this->respondWithSuccessCodeSent($verification);
    }

    /**
     * @param Verification $verification
     * @return mixed
     */
    private function respondWithSuccessCodeSent(Verification $verification)
    {
        return response()->json([
            'status' => 200,
            'message' => 'Successfully sent verification code',
            'data' => [
                'verification_status' => $verification->verification_status,
                'expires_at' => 'I still need to do this part',
            ],
        ], 200);
    }
}
