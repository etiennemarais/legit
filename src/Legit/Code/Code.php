<?php
namespace Legit\Code;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Infrastructure\Traits\MultiTenantScopable;

class Code extends Model
{
    use MultiTenantScopable;

    protected $table = 'code';

    protected $fillable = [
        'country_id',
        'verification_id',
        'code',
    ];

//    public static $rules = [
//        'client_user_id' => 'required',
//        'phone_number' => 'required|region|min:11',
//    ];

    public $readable = 'Code';

    /**
     * @return HasOne
     */
    public function verification()
    {
        return $this->hasOne('Legit\Verification\Verification', 'id', 'verification_id');
    }
}
