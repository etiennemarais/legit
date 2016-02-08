<?php
namespace Legit\Verification;

use Illuminate\Database\Eloquent\Model;
use Infrastructure\Traits\MultiTenantScopable;

class Verification extends Model
{
    use MultiTenantScopable;

    protected $table = 'verification';
    public $timestamps = false;

    protected $fillable = [
        'client_user_id',
        'country_id',
        'phone_number',
        'verification_status',
    ];

    public static $rules = [
        'client_user_id' => 'required',
        'phone_number' => 'required',
    ];

    public $readable = 'Verification';
}
