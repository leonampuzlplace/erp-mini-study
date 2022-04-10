<?php

namespace App\Models;

use App\Http\Dto\State\StateDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\LaravelData\WithData;

class State extends Model
{
    use HasFactory;
    use SoftDeletes;
    use WithData;

    protected $table = 'state';
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    protected $dataClass = StateDto::class;
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
}
