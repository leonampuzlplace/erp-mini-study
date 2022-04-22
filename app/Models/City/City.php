<?php

namespace App\Models\City;

use App\Http\Dto\City\CityDto;
use App\Models\State\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\LaravelData\WithData;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;
    use WithData;

    protected $table = 'city';
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    protected $dataClass = CityDto::class;
    public $timestamps = true;

    protected $hidden = [
        'deleted_at',
        'created_at', 
        'updated_at'
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
    
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
