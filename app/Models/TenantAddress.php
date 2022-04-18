<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantAddress extends Model
{
    use HasFactory;

    protected $table = 'tenant_address';
    public $timestamps = false;

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected $guarded = ['id'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
