<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonContact extends Model
{
    use HasFactory;

    protected $table = 'person_contact';
    public $timestamps = false;

    protected $casts = [
        'is_default' => 'boolean',
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
        static::saving(fn ($model) => $model->ein = onlyNumbers($model->ein));

        // Formatar dados antes de recuperar a informação
        static::retrieved(fn ($model) => $model->ein = formatCpfCnpj($model->ein));
    }    
}
