<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'company';
    protected $primaryKey = 'company_id';
    protected $dates = ['deleted_at'];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'company_id',
        'business_name',
        'alias_name',
        'company_ein',
        'state_registration',
        'icms_taxpayer',
        'municipal_registration',
        'note_general',
        'internet_page',
    ];
}
