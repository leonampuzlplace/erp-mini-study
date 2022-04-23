<?php

namespace App\Models\Brand;

use App\Http\Dto\Brand\BrandDto;
use App\Traits\TenantAbleTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class Brand extends Model
{
    use HasFactory;
    use WithData;
    use TenantAbleTrait;

    protected $table = 'brand';
    protected $dates = ['deleted_at'];
    protected $dataClass = BrandDto::class;
    public $timestamps = true;

    protected $hidden = [
    ];

    protected $casts = [
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
        static::saving(fn ($model) => $model);

        // Formatar dados antes de recuperar a informação
        static::retrieved(fn ($model) => $model);
    }
}
