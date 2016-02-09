<?php
namespace Legit\Validation;

use Illuminate\Support\Facades\Config;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;

class Phone
{
    private $numberUtil;

    public function __construct()
    {
        $this->numberUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return boolean
     */
    public function validateRegion($attribute, $value, $parameters, $validator)
    {
        try {
            $validatedNumber = $this->numberUtil->parse($value, Config::get('country_iso'));
        } catch (NumberParseException $e) {
            return false;
        }

        return $this->returnIsValidNumber($validatedNumber);
    }

    /**
     * @param $validatedNumber
     * @return boolean
     */
    private function returnIsValidNumber($validatedNumber)
    {
        if ($this->numberUtil->isValidNumber($validatedNumber)
            && $this->numberUtil->getNumberType($validatedNumber) === PhoneNumberType::MOBILE) {

            return true;
        }

        return false;
    }
}