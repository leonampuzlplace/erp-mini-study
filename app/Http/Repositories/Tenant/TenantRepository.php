<?php

namespace App\Http\Repositories\Tenant;

use App\Exceptions\ModelNotFoundException;
use App\Http\Repositories\BaseRepository;
use App\Models\Tenant\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\Data;

class TenantRepository extends BaseRepository
{
  public function __construct(Tenant $model)
  {
    parent::__construct($model);
  }

  public static function make(): Self
  {
    return new self(new Tenant);
  }

  /**
   * Método executado dentro de BaseRepository.index()
   * Adicionar join de tabelas e mostrar colunas específicas
   *
   * @param Builder $queryBuilder
   * @return array
   * Retornar um array contendo queryBuilder e string de colunas a serem exibidas
   */
  public function indexInside(Builder $queryBuilder): array
  {
    return [
      $queryBuilder
        ->leftJoin('tenant_address', 'tenant_address.tenant_id', 'tenant.id')
        ->leftJoin('tenant_contact', 'tenant_contact.tenant_id', 'tenant.id')
        ->leftJoin('city', 'city.id', 'tenant_address.city_id')
        ->leftJoin('state', 'state.id', 'city.state_id')
        ->where('tenant_address.is_default', '1')
        ->where('tenant_contact.is_default', '1'),
      'tenant.*, '.
      'tenant_address.zipcode         as tenant_address_zipcode, '.
      'tenant_address.address         as tenant_address_address, ' .
      'tenant_address.address_number  as tenant_address_address_number, ' .
      'tenant_address.complement      as tenant_address_complement, ' .
      'tenant_address.district        as tenant_address_district, ' .
      'tenant_address.reference_point as tenant_address_reference_point, ' .
      'tenant_contact.name            as tenant_contact_name, ' .
      'tenant_contact.ein             as tenant_contact_ein, ' .
      'tenant_contact.type            as tenant_contact_type, ' .
      'tenant_contact.note            as tenant_contact_note, ' .
      'tenant_contact.phone           as tenant_contact_phone, ' .
      'tenant_contact.email           as tenant_contact_email, ' .
      'city.id                        as city_id, ' .
      'city.name                      as city_name, ' .
      'city.ibge_code                 as city_ibge_code, ' .
      'state.name                     as state_name, ' .
      'state.abbreviation             as state_abbreviation'
    ];
  }

  /**
   * Localizar um único registro por ID
   * Acrescenta with para mostrar relacionamentos
   *
   * @param integer $id
   * @return Data
   */
  public function show(int $id): Data
  {
    $modelFound = $this->model
      ->whereId($id)
      ->with('tenantAddress.city.state')
      ->with('tenantContact')
      ->first();

    throw_if(!$modelFound, new ModelNotFoundException(trans('message_lang.model_not_found') . ' id: ' . $id));
    return $modelFound->getData();
  }

  /**
   * Salvar registro e retornar DTO
   * Acrescenta createMany para salvar relacionamentos
   * 
   * @param Data $dto
   * @return Data
   */
  public function store(Data $dto): Data
  {
    $dto->id = null;
    $data = $dto->toArray();
    $executeStore = function ($data) {
      $modelFound = $this->model->create($data);
      $modelFound->tenantAddress()->createMany($data['tenant_address']);
      $modelFound->tenantContact()->createMany($data['tenant_contact']);

      return $this->show($modelFound->id);
    };

    // Controle de Transação
    return match ($this->isTransaction()) {
      true => DB::transaction(fn () => $executeStore($data)),
      false => $executeStore($data),
    };
  }

  /**
   * Atualizar Registro e retorna DTO atualizado
   *
   * @param integer $id
   * @param Data $dto
   * @return Data
   */
  public function update(int $id, Data $dto): Data
  {
    $dto->id = $id;
    $data = $dto->toArray();
    $executeUpdate = function ($id, $data) {
      $modelFound = $this->model->findOrFail($id);

      // Atualizar Tenant
      tap($modelFound)->update($data);

      // Atualizar TenantAddress
      $modelFound->tenantAddress()->delete();
      $modelFound->tenantAddress()->createMany($data['tenant_address']);

      // Atualizar TenantContact
      $modelFound->tenantContact()->delete();
      $modelFound->tenantContact()->createMany($data['tenant_contact']);

      // Carregar relacionamentos
      $modelFound
        ->load('tenantAddress.city.state')
        ->load('tenantContact');
      
      return $modelFound->getData();
    };

    // Controle de Transação
    return match ($this->isTransaction()) {
      true => DB::transaction(fn () => $executeUpdate($id, $data)),
      false => $executeUpdate($id, $data),
    };
  }
}
