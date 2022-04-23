<?php

namespace App\Models\Stock;

use App\Http\Dto\Stock\StockDto;
use App\Models\Brand\Brand;
use App\Models\Category\Category;
use App\Models\Unit\Unit;
use App\Traits\TenantAbleTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class Stock extends Model
{
    use HasFactory;
    use WithData;
    use TenantAbleTrait;

    protected $table = 'stock';
    protected $dates = ['deleted_at'];
    protected $dataClass = StockDto::class;
    public $timestamps = true;

    protected $hidden = [
    ];

    protected $casts = [
        'is_service' => 'boolean',
        'cost_price' => 'float',
        'sale_price' => 'float',
        'minimum_quantity' => 'float',
        'current_quantity' => 'float',
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

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
