<?php

namespace App\Models;

use App\Http\Dto\City\CityDto;
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

    protected $fillable = [
        'id',
        'state_name',
        'state_abbreviation',
    ];

    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }
}
