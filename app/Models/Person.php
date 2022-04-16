<?php

namespace App\Models;

use App\Http\Dto\Person\PersonDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\LaravelData\WithData;

class Person extends Model
{
    use HasFactory;
    use SoftDeletes;
    use WithData;

    protected $table = 'person';
    protected $dates = ['deleted_at'];
    protected $dataClass = PersonDto::class;
    public $timestamps = true;

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'icms_taxpayer' => 'boolean',
    ];

    protected $fillable = [
        'tenant_id',
        'business_name',
        'alias_name',
        'ein',
        'state_registration',
        'icms_taxpayer',
        'municipal_registration',
        'note',
        'internet_page',
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
