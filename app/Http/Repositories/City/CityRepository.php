<?php

namespace App\Http\Repositories\City;

use App\Exceptions\ModelNotFoundException;
use App\Http\Repositories\BaseRepository;
use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelData\Data;

class CityRepository extends BaseRepository
{
  public function __construct(City $city)
  {
    parent::__construct($city);
  }

  public static function make(): Self
  {
    return new self(new City);
  }

  /**
   * Método executado dentro de BaseRepository.index()
   * Adicionar join de tabelas e mostrar colunas específicas
   *
   * @param Builder $queryBuilder
   * @return array
   */
  public function indexInside(Builder $queryBuilder): array
  {
    return [
      $queryBuilder->leftJoin('state', 'state.id', 'city.state_id'),
      'city.*, ' .
      'state.state_name, ' .
      'state.state_abbreviation'
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
      ->with('state')
      ->first();

    throw_if(!$modelFound, new ModelNotFoundException(__('message_lang.model_not_found').' id: '.$id));
    return $modelFound->getData();
  }
}
