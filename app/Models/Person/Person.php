<?php

namespace App\Models\Person;

use App\Http\Dto\Person\PersonDto;
use App\Traits\TenantAbleTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class Person extends Model
{
    use HasFactory;
    use WithData;
    use TenantAbleTrait;
        
    protected $table = 'person';
    protected $dates = ['deleted_at'];
    protected $dataClass = PersonDto::class;
    public $timestamps = true;

    protected $hidden = [
    ];

    protected $casts = [
        'icms_taxpayer' => 'boolean',
        'is_customer' => 'boolean',
        'is_seller' => 'boolean',
        'is_supplier' => 'boolean',
        'is_carrier' => 'boolean',
        'is_technician' => 'boolean',
        'is_employee' => 'boolean',
        'is_other' => 'boolean',
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
        static::saving(fn ($model) => $model->ein = onlyNumbers($model->ein ?? ''));

        // Formatar dados antes de recuperar a informação
        static::retrieved(fn ($model) => $model->ein = formatCpfCnpj($model->ein ?? ''));
    }    

    public function personAddress()
    {
        return $this->hasMany(PersonAddress::class);
    }

    public function personContact()
    {
        return $this->hasMany(PersonContact::class);
    }
}
