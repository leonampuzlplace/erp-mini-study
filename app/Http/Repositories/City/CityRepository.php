<?php

namespace App\Http\Repositories\City;

use App\Http\Repositories\BaseRepository;
use App\Models\City;
use Spatie\LaravelData\Data;

class CityRepository extends BaseRepository
{
  private function __construct(City $city)
  {
    parent::__construct($city);
  }

  public static function make(): Self
  {
    return new self(new City);
  }

  public function index(array $pageOption = [], array $filter = []): array
  {
    $this->pageOption = $pageOption;
    $this->filter = $filter;
    $queryBuilder = $this->indexBuilder();

    // Campos com join
    $queryBuilder->leftJoin('state', 'state.id', 'city.state_id');
    $selectRaw = 'city.*, ' .
                 'state.state_name, ' .
                 'state.state_abbreviation';

    // Paginação e Retorno dos dados
    return $this->indexGetAndPaginate($queryBuilder, $selectRaw);
  }

  public function show(int $id): Data
  {
    $modelFound = $this->model
      ->where('id', $id)
      ->with('state')
      ->first();

    throw_if(!$modelFound, new \Exception('No query results for $id = '. $id));
    return $modelFound->getData();
  }
}