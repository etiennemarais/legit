<?php
namespace Legit\Verification;

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
     * @param string $phoneNumber
     * @param string $clientUserId
     * @return boolean
     */
    public function isPhoneNumberVerified($phoneNumber, $clientUserId)
    {
        $verified = $this->model->where([
            'phone_number' => $phoneNumber,
            'client_user_id' => $clientUserId
        ])->first();

        if (is_null($verified)) {
            $verified = $this->model->create([
                'phone_number' => $phoneNumber,
                'client_user_id' => $clientUserId,
                'verification_status' => 'unverified',
            ]);
        }

        return ($verified->verification_status === 'verified');
    }
}