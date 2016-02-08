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
}