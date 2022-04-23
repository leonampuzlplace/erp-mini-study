<?php

namespace App\Models\Tenant;

use App\Http\Dto\Tenant\TenantDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class Tenant extends Model
{
    use HasFactory;
    use WithData;

    protected $table = 'tenant';
    protected $dates = ['deleted_at'];
    protected $dataClass = TenantDto::class;
    public $timestamps = true;

    protected $hidden = [
    ];

    protected $casts = [
        'icms_taxpayer' => 'boolean',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        // Formatar dados antes de salvar a informação
        static::saving(fn ($model) => $model->ein = onlyNumbers($model->ein ?? ''));

        // Formatar dados antes de recuperar a informação
        static::retrieved(fn ($model) => $model->ein = formatCpfCnpj($model->ein ?? ''));
    }

    public function tenantAddress()
    {
        return $this->hasMany(TenantAddress::class);
    }

    public function tenantContact()
    {
        return $this->hasMany(TenantContact::class);
    }
}
