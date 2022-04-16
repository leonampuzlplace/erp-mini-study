<?php

namespace App\Models;

use App\Http\Dto\Person\PersonTypeDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\LaravelData\WithData;

class PersonType extends Model
{
    use HasFactory;
    use SoftDeletes;
    use WithData;

    protected $table = 'person_type';
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    protected $dataClass = PersonTypeDto::class;
    public $timestamps = true;

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'name',
    ];
}
