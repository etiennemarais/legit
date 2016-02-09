<?php
namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

        // TODO 406 This phone number is not valid, add validator extension

        if ($validator->fails()) {
            $message = $this->getMessageFromValidator($validator);

            return $this->respondWithMissingField($message);
        }

        $phoneNumber = $request->input('phone_number');
        $clientUserId = $request->input('client_user_id');

        $isVerified = $repository->isPhoneNumberVerified($phoneNumber, $clientUserId);

        return ($isVerified)
            ? $this->respondWithVerified($phoneNumber, $clientUserId)
            : $this->respondWithNotVerified($phoneNumber, $clientUserId);
    }

    /**
     * @param $validator
     * @return string
     */
    private function getMessageFromValidator($validator)
    {
        $required = [];
        $messages = $validator->errors()->toArray();
        foreach($messages as $field => $message) {
            if (strpos($message[0], 'required')) {
                $required[] = $field;
            }
        }

        if (count($required) > 0) {
            $fields = implode(', ', $required);
            $message = "Missing required fields $fields";
        }

        return $message;
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
}
