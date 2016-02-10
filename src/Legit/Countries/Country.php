<?php
namespace Legit\Countries;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';

    protected $fillable = [
        'country_code',
        'country_iso',
        'api_key',
        'status',
    ];

    public static $rules = [
        'country_code' => 'required',
        'country_iso' => 'required',
        'api_key' => 'required',
    ];

    public $readable = 'Country';
}
