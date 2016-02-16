<?php
namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Legit\Code\Code;
use Legit\Verification\Verification;
use Legit\Verification\VerificationRepository;
use Symfony\Component\HttpFoundation\Response;

class VerificationController extends Controller
{
    /**
     * @param Request $request
     * @param VerificationRepository $repository
     * @return Response
     */
    public function check(Request $request, VerificationRepository $repository)
    {
        $validator = Validator::make($request->all(), Verification::$rules);

        if ($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        }

        $phoneNumber = $request->input('phone_number');
        $clientUserId = $request->input('client_user_id');

        $isVerified = $repository->isPhoneNumberVerified([
            'phone_number' => $phoneNumber,
            'client_user_id' => $clientUserId,
        ]);

        return ($isVerified)
            ? $this->respondWithVerified($phoneNumber, $clientUserId)
            : $this->respondWithNotVerified($phoneNumber, $clientUserId);
    }

    /**
     * @param $phoneNumber
     * @param $clientUserId
     * @return Response
     */
    private function respondWithNotVerified($phoneNumber, $clientUserId)
    {
        return response()->json([
            'status' => 403,
            'message' => 'This phone number is not verified',
            'data' => [
                'phone_number' => $phoneNumber,
                'client_user_id' => $clientUserId,
            ],
        ], 403);
    }

    /**
     * @param $phoneNumber
     * @param $clientUserId
     * @return Response
     */
    private function respondWithVerified($phoneNumber, $clientUserId)
    {
        return response()->json([
            'status' => 200,
            'message' => 'This phone number is verified',
            'data' => [
                'phone_number' => $phoneNumber,
                'client_user_id' => $clientUserId,
            ],
        ], 200);
    }

    /**
     * @param Request $request
     * @param VerificationRepository $repository
     * @return Response
     */
    public function verify(Request $request, VerificationRepository $repository)
    {
        $validator = Validator::make(
            $request->all(),
            array_merge(Verification::$rules, Code::$rules)
        );

        if ($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        }

        $phoneNumber = $request->input('phone_number');
        $clientUserId = $request->input('client_user_id');

        $verification = $repository->findWithAttributes([
            'phone_number' => $phoneNumber,
            'client_user_id' => $clientUserId,
        ]);

        $isValidCode = $repository->isValidCode(
            $verification,
            $request->input('code')
        );

        if ($isValidCode) {
            $verification->verification_status = 'verified';
            $verification->save();

            return $this->respondWithVerified($phoneNumber, $clientUserId);
        }

        return $this->respondWithNotVerified($phoneNumber, $clientUserId);
    }
}
