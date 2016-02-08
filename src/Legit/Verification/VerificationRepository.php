<?php
namespace Legit\Verification;

use Illuminate\Support\Facades\DB;
use Legit\Repository;

class VerificationRepository extends Repository
{
    /**
     * @param Verification $model
     */
    public function __construct(Verification $model)
    {
        $this->model = $model;
    }

    /**
     * @param $phoneNumber
     * @param $clientUserId
     * @return boolean
     */
    public function isPhoneNumberVerified($phoneNumber, $clientUserId)
    {
        $verified = $this->model
            ->where(['phone_number' => $phoneNumber, 'client_user_id' => $clientUserId])
            ->first();

        return ($verified->verification_status === 'verified');
    }
}