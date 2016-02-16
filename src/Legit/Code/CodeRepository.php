<?php
namespace Legit\Code;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Legit\Repository;

class CodeRepository extends Repository
{
    /**
     * @param Code $model
     */
    public function __construct(Code $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function createWithAttributes(array $attributes)
    {
        $vars = array_merge([
            'code' => $this->generateToken(),
            'expires_at' => Carbon::now()->addMinutes(10)->toDateTimeString(),
        ], $attributes);

        return parent::createWithAttributes($vars);
    }

    /**
     * @return string
     */
    protected function generateToken()
    {
        $this->reseed();
        $length = env('OTP_LENTGH');
        $token = "";
        $vowels = 'aeuyAEUY';
        $consonants = 'bdghjmnpqrstvzBDGHJLMNPQRSTVWXZ123456789';
        $numbers = '1234567890';

        for ($i = 1; $i <= $length; $i++) {
            $use = ($i % 2) ? $consonants : $vowels;

            if (env('OTP_ONLYNUMBERS')) {
                $use = $numbers;
            }

            $token .= $use[(rand() % strlen($use))];
        }

        return $token;
    }

    private function reseed()
    {
        mt_srand(crc32(microtime()));
    }
}
