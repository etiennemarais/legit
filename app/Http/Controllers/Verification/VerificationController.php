<?php
namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class VerificationController extends Controller
{
    public function check(Request $request)
    {
        // 400 missing required field
        // 406 This phone number is not valid

        // 403 This phone number is not verified
        // 200 success
    }
}
