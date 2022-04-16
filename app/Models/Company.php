<?php

namespace App\Models;

use App\Http\Dto\Company\CompanyDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\LaravelData\WithData;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;
    use WithData;

    protected $table = 'company';
    protected $dates = ['deleted_at'];
    protected $dataClass = CompanyDto::class;
    public $timestamps = true;

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'icms_taxpayer' => 'boolean',
    ];

    protected $fillable = [
        'business_name',
        'alias_name',
        'company_ein',
        'state_registration',
        'icms_taxpayer',
        'municipal_registration',
        'note_general',
        'internet_page',
    ];

    public function companyAddress()
    {
        return $this->hasMany(CompanyAddress::class);
    }

    public function companyContact()
    {
        return $this->hasMany(CompanyContact::class);
    }
}
