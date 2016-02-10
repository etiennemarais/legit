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

    public $readable = 'Code';

    /**
     * @return HasOne
     */
    public function verification()
    {
        return $this->hasOne('Legit\Verification\Verification', 'id', 'verification_id');
    }
}
