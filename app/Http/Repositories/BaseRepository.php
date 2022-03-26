<?php

namespace App\Http\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

abstract class BaseRepository
{
  private $withTransaction = false;
  protected $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function getModel(): Model
  {
    return $this->model;
  }

  protected function persist(\Closure $function)
  {
    try {
      if (!$this->withTransaction) {
        return $function();
      }
    } catch (Throwable $exception) {
      throw $exception;
    }
    return DB::transaction($function);
  }

  protected function withTransaction()
  {
    $this->withTransaction = true;
    return $this;
  }
  // Exemplo de uso
  // return $this->withTransaction()->persist(function () use ($request) {
  //   return $this->repository->store($request->all());
  // });

  public function paginateOptionsValidated(array $paginateOptions = []): array
  {
    return [
      'perPage' => $paginateOptions['perPage'] ?? 10,
      'page' => $paginateOptions['page'] ?? 1,
      'paginateType' => intval($paginateOptions['paginateType'] ?? 0),
      'columns' => $paginateOptions['columns'] ?? ['*'],
      'cursor' => $paginateOptions['cursor'] ?? null,
      'onlyData' => $paginateOptions['onlyData'] ?? 0,
    ];
  }
  
  public function filterValidated(array $filter = []): array
  {
    return [
      'orderBy' => $filter['orderBy'] ?? '',
      'searchField' => $filter['searchField'] ?? '',
      'searchValue' => $filter['searchValue'] ?? '',
      'searchType' => intval($filter['searchType'] ?? 0),
      'customSearchValue' => $filter['customSearchValue'] ?? [''],
      'searchValueAndType' => match (intval($filter['searchType'] ?? 0)) {
        0 => $filter['searchValue'] ?? ''.'%',     // Inicio
        1 => '%'.$filter['searchValue'] ?? '',     // Fim      
        2 => '%'.$filter['searchValue'] ?? ''.'%', // Qualquer Parte
        3 => $filter['searchValue'] ?? '',         // Igual
      },
    ];
  }

  public function prepareBuilder(): Builder
  {
    return $this->model->where(
      $this->model->getTable().'.'.$this->model->getKeyName(), '>', 0
    );
  }
}

