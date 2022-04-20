<?php

namespace App\Models;

use App\Http\Dto\Person\PersonDto;
use App\Traits\TenantAbleTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\LaravelData\WithData;

class Person extends Model
{
    use HasFactory;
    use SoftDeletes;
    use WithData;
    use TenantAbleTrait;
        
    protected $table = 'person';
    protected $dates = ['deleted_at'];
    protected $dataClass = PersonDto::class;
    public $timestamps = true;

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'icms_taxpayer' => 'boolean',
        'is_customer' => 'boolean',
        'is_seller' => 'boolean',
        'is_supplier' => 'boolean',
        'is_carrier' => 'boolean',
        'is_technician' => 'boolean',
        'is_employee' => 'boolean',
        'is_other' => 'boolean',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function personAddress()
    {
        return $this->hasMany(PersonAddress::class);
    }

    public function personContact()
    {
        return $this->hasMany(PersonContact::class);
    }
}
