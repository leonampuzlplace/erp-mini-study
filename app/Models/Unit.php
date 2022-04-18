<?php

namespace App\Models;

use App\Http\Dto\Unit\UnitDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\LaravelData\WithData;

class Unit extends Model
{
    use HasFactory;
    use SoftDeletes;
    use WithData;    

    protected $table = 'unit';
    protected $dates = ['deleted_at'];
    protected $dataClass = UnitDto::class;
    public $timestamps = true;

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
    ];

    protected $guarded = ['id'];

}
