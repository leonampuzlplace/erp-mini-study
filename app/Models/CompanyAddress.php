<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
    use HasFactory;

    protected $table = 'company_address';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'company_id',
        'is_default',
        'zipcode',
        'address',
        'address_number',
        'complement',
        'district',
        'city_id',
        'reference_point',
    ];

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }
}
