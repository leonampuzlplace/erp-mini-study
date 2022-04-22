<?php

namespace App\Models\User;

use App\Http\Dto\User\RoleDto;
use App\Traits\TenantAbleTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\LaravelData\WithData;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;
    use WithData;
    use TenantAbleTrait;

    protected $table = 'role';
    protected $dates = ['deleted_at'];
    protected $dataClass = RoleDto::class;
    public $timestamps = true;

    protected $hidden = [
        'deleted_at',
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

    public function rolePermission()
    {
        return $this->hasMany(RolePermission::class);
    }
}
