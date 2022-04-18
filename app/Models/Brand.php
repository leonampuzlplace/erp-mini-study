<?php

namespace App\Models;

use App\Http\Dto\Brand\BrandDto;
use App\Traits\TenantAbleTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\LaravelData\WithData;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;
    use WithData;
    use TenantAbleTrait;

    protected $table = 'brand';
    protected $dates = ['deleted_at'];
    protected $dataClass = BrandDto::class;
    public $timestamps = true;

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
    ];

    protected $guarded = ['id'];
}
