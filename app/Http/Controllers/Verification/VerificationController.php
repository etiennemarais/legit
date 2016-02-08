<?php
namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Legit\Verification\Verification;
use Legit\Verification\VerificationRepository;

class VerificationController extends Controller
{
    public function check(Request $request, VerificationRepository $repository)
    {
        $validator = Validator::make($request->all(), Verification::$rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Missing required field \'phone_number\'',
            ], 400);
        }

        // 400 missing required field
        // 406 This phone number is not valid

        // 403 This phone number is not verified
        // 200 success
    }
}
