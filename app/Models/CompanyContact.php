<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyContact extends Model
{
    use HasFactory;

    protected $table = 'company_contact';
    public $timestamps = false;

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected $fillable = [
        'is_default',
        'contact_name',
        'contact_ein',
        'contact_type',
        'contact_note',
        'phone',
        'email',
    ];
}
