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

  public function destroy(int $id): bool
  {
    $executeDestroy = function ($id) {
      $modelFound = $this->model->findOrFail($id);
      return $modelFound->delete();
    };

    return match ($this->withTransaction) {
      true => DB::transaction(
        function () use ($executeDestroy, $id) {
          return $executeDestroy($id);
        }
      ),
      false => $executeDestroy($id),
    };
  }

  public function show(int $id, bool $resultIsModel = false): Model | array
  {
    $modelFound = $this->model->findOrFail($id);
    return $resultIsModel
      ? $modelFound
      : $modelFound->toArray();
  }

  public function store(array $data): array
  {
    $executeStore = function ($data) {
      return $this->model->create($data)->toArray();
    };

    return match ($this->withTransaction) {
      true => DB::transaction(
        function () use ($executeStore, $data) {
          return $executeStore($data);
        }
      ),
      false => $executeStore($data),
    };
  }

  public function update(int $id, array $data): array
  {
    $executeUpdate = function ($id, $data) {
      $modelFound = $this->show($id, true);
      tap($modelFound)->update($data);
      return $modelFound->toArray();
    };

    return match ($this->withTransaction) {
      true => DB::transaction(
        function () use ($executeUpdate, $id, $data) {
          return $executeUpdate($id, $data);
        }
      ),
      false => $executeUpdate($id, $data),
    };    
  }

  public function withTransaction(bool $active)
  {
    $this->withTransaction = $active;
    return $this;
  }

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
}

