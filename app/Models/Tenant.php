<?php

namespace App\Models;

use App\Http\Dto\Tenant\TenantDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\LaravelData\WithData;

class Tenant extends Model
{
    use HasFactory;
    use SoftDeletes;
    use WithData;

    protected $table = 'tenant';
    protected $dates = ['deleted_at'];
    protected $dataClass = TenantDto::class;
    public $timestamps = true;

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'icms_taxpayer' => 'boolean',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function tenantAddress()
    {
        return $this->hasMany(TenantAddress::class);
    }

    public function tenantContact()
    {
        return $this->hasMany(TenantContact::class);
    }
}
