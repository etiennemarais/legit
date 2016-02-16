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
     * @param array $attributes
     * @return boolean
     */
    public function isPhoneNumberVerified(array $attributes)
    {
        $verified = parent::findWithAttributes($attributes);

        if (is_null($verified)) {
            $verified = $this->createIfNotExists($attributes);
        }

        return ($verified->verification_status === 'verified');
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function findWithAttributes(array $attributes)
    {
        $verification = parent::findWithAttributes($attributes);

        if (is_null($verification)) {
            $verification = $this->createIfNotExists($attributes);
        }

        return $verification;
    }

    /**
     * NOTE: $verification is passed by reference so the update would cascade back up.
     *
     * @param Verification $verification
     * @return Verification
     */
    public function setAwaitingVerificationStatus(Verification $verification)
    {
        $verification->verification_status = 'awaiting verification';
        $verification->save();
    }

    /**
     * @param Verification $verification
     * @param $code
     * @return boolean
     */
    public function isValidCode(Verification $verification, $code)
    {
        $code = $verification->codes()->where('code', $code)->first();

        return (is_null($code) ? false : true);
    }

    /**
     * @param array $attributes
     * @return static
     */
    private function createIfNotExists(array $attributes)
    {
        $vars = array_merge($attributes, [
            'verification_status' => 'unverified',
        ]);

        return $this->createWithAttributes($vars);
    }
}