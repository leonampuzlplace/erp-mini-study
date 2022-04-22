<?php

namespace App\Models\User;

use App\Http\Dto\User\RolePermissionDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class RolePermission extends Model
{
    use HasFactory;
    use WithData;
    
    protected $table = 'role_permission';
    protected $dataClass = RolePermissionDto::class;
    public $timestamps = false;

    protected $hidden = [
    ];

    protected $casts = [
        'is_allowed' => 'boolean',
    ];

    protected $guarded = [
        'id',
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
