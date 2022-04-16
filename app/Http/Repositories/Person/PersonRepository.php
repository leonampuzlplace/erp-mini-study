<?php

namespace App\Http\Repositories\Person;

use App\Exceptions\ModelNotFoundException;
use App\Http\Repositories\BaseRepository;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\Data;

class PersonRepository extends BaseRepository
{
  public function __construct(Person $person)
  {
    parent::__construct($person);
  }

  public static function make(): Self
  {
    return new self(new Person);
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
        ->leftJoin('person_address', 'person_address.person_id', 'person.id')
        ->leftJoin('person_contact', 'person_contact.person_id', 'person.id')
        ->leftJoin('city', 'city.id', 'person_address.city_id')
        ->leftJoin('state', 'state.id', 'city.state_id')
        ->where('person_address.is_default', '1')
        ->where('person_contact.is_default', '1'),
      'person.*, ' .
      'person_address.zipcode         as person_address_zipcode, ' .
      'person_address.address         as person_address_address, ' .
      'person_address.address_number  as person_address_address_number, ' .
      'person_address.complement      as person_address_complement, ' .
      'person_address.district        as person_address_district, ' .
      'person_address.reference_point as person_address_reference_point, ' .
      'person_contact.name            as person_contact_name, ' .
      'person_contact.ein             as person_contact_ein, ' .
      'person_contact.type            as person_contact_type, ' .
      'person_contact.note            as person_contact_note, ' .
      'person_contact.phone           as person_contact_phone, ' .
      'person_contact.email           as person_contact_email, ' .
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
      ->where('id', $id)
      ->with('personAddress.city.state')
      ->with('personContact')
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
      $modelFound->personAddress()->createMany($data['person_address']);
      $modelFound->personContact()->createMany($data['person_contact']);

      return $this->show($modelFound->id);
    };

    return match ($this->getWithTransaction()) {
      true => DB::transaction(
        function () use ($executeStore, $data) {
          return $executeStore($data);
        }
      ),
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

      // Atualizar Person
      tap($modelFound)->update($data);

      // Atualizar PersonAddress
      $modelFound->personAddress()->where('person_address.person_id', $id)->delete();
      $modelFound->personAddress()->createMany($data['person_address']);

      // Atualizar PersonContact
      $modelFound->personContact()->where('person_contact.person_id', $id)->delete();
      $modelFound->personContact()->createMany($data['person_contact']);

      // Carregar relacionamentos
      $modelFound
        ->load('personAddress.city.state')
        ->load('personContact');

      return $modelFound->getData();
    };

    return match ($this->getWithTransaction()) {
      true => DB::transaction(
        function () use ($executeUpdate, $id, $data) {
          return $executeUpdate($id, $data);
        }
      ),
      false => $executeUpdate($id, $data),
    };
  }
}
