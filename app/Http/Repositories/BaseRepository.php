<?php

namespace App\Http\Repositories;

use App\Http\Dto\Company\CompanyDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
  private $withTransaction = false;
  protected $model;
  public array $pageOptions = [];
  public array $filter = [];

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  // Deletar registro
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

  // Obter vários registros a partir de uma configuração (pageOptions, Filter)
  public function index(array $pageOptions = [], array $filter = []): array
  {
    $this->pageOptions = $pageOptions;
    $this->filter = $filter;
    $queryBuilder = $this->indexBuilder();
    $selectRaw = $this->model->getTable().'.*';

    // Paginação e Retorno dos dados
    return $this->indexGetAndPaginate($queryBuilder, $selectRaw);
  }

  // Construção do Index
  public function indexBuilder(): Builder
  {
    $this->pageOptionsValidated();
    $this->filterValidated();
    $queryBuilder = $this->model->newQuery();

    // Fitlro de Ordenação
    foreach ($this->filter['orderBy'] as $value) {
      if (($value) && ($value['column']) && ($value['direction'])) {
        $queryBuilder->orderBy($value['column'], $value['direction']);
      }
    }

    // Filtro de pesquisa padrão
    if (($this->filter['searchField']) && ($this->filter['searchValue'])) {
      $queryBuilder->where($this->filter['searchField'], 'like', $this->filter['searchValueAndType']);
    }

    // Filtro de período de cadastro
    if (($this->filter['createdAtStart']) && ($this->filter['createdAtEnd'])) {
      $queryBuilder->whereBetween($this->model->getTable() . '.created_at', [
        formatDate($this->filter['createdAtStart'], startOfDay: true),
        formatDate($this->filter['createdAtEnd'], endOfDay: true),
      ]);
    }

    // Filtro de período de alteração
    if (($this->filter['updatedAtStart']) && ($this->filter['updatedAtEnd'])) {
      $queryBuilder->whereBetween($this->model->getTable() . '.updated_at', [
        formatDate($this->filter['updatedAtStart'], startOfDay: true),
        formatDate($this->filter['updatedAtEnd'], endOfDay: true),
      ]);
    }


    // Filtro de registros apagados
    if (intVal($this->filter['onlyTrashed']) === 1) {
      $queryBuilder->onlyTrashed();
    }

    return $queryBuilder;
  }

  // Paginação do Index
  public function indexGetAndPaginate(Builder $queryBuilder, String $selectRaw = '*'): array
  {
    // Campos a serem exibidos (Necessário se houver join)
    (($this->pageOptions['columns'][0] === '*') && (count($this->pageOptions['columns']) === 1))
      ? $queryBuilder->selectRaw($selectRaw)
      : $queryBuilder->selectRaw(implode(', ', $this->pageOptions['columns']));

    // Paginação
    $queryBuilder = match ($this->pageOptions['paginateType']) {
      0 => $queryBuilder->paginate($this->pageOptions['perPage'], null, 'page', $this->pageOptions['page']),
      1 => $queryBuilder->simplePaginate($this->pageOptions['perPage'], null, 'page', $this->pageOptions['page']),
      2 => $queryBuilder->cursorPaginate($this->pageOptions['perPage'], null, 'cursor', $this->pageOptions['cursor']),
      default => $queryBuilder->get(), // Sem Paginação
    };

    // Resultado
    return (($this->pageOptions['onlyData'] == 1) && ($this->pageOptions['paginateType'] <= 2))
      ? $queryBuilder->toArray()['data']
      : $queryBuilder->toArray();
  }

  // Obter único registro
  public function show(int $id, bool $resultIsModel = false): Model | CompanyDto
  {
    $modelFound = $this->model->findOrFail($id);
    return $resultIsModel
      ? $modelFound
      : CompanyDto::from($modelFound);
  }

  // Inserir novo registro
  public function store(CompanyDto $companyDto): CompanyDto
  {
    $data = $companyDto->all();
    $executeStore = function ($data) {
      return CompanyDto::from($this->model->create($data));
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

  // Atualizar registro
  public function update(int $id, CompanyDto $companyDto): CompanyDto
  {
    $companyDto->id = $id;
    $data = $companyDto->all();
    $executeUpdate = function ($id, $data) {
      $modelFound = $this->show($id, true);
      tap($modelFound)->update($data);
      return CompanyDto::from($modelFound);
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

  // Controle de Transação
  public function withTransaction(bool $active): self
  {
    $this->withTransaction = $active;
    return $this;
  }

  // Validar opções da página
  public function pageOptionsValidated(): void
  {
    $this->pageOptions = [
      'perPage' => $this->pageOptions['perPage'] ?? 10,
      'page' => $this->pageOptions['page'] ?? 1,
      'paginateType' => intval($this->pageOptions['paginateType'] ?? 0),
      'columns' => $this->pageOptions['columns'] ?? ['*'],
      'cursor' => $this->pageOptions['cursor'] ?? null,
      'onlyData' => $this->pageOptions['onlyData'] ?? 0,
    ];
  }
  
  // Validar Filtro
  public function filterValidated(): void
  {
    $this->filter = [
      'orderBy' => $this->filter['orderBy'] ?? [''],
      'searchField' => $this->filter['searchField'] ?? '',
      'searchValue' => $this->filter['searchValue'] ?? '',
      'searchType' => intval($this->filter['searchType'] ?? 0),
      'customSearchValue' => $this->filter['customSearchValue'] ?? [''],
      'createdAtStart' => $this->filter['createdAtStart'] ?? '',
      'createdAtEnd' => $this->filter['createdAtEnd'] ?? '',
      'updatedAtStart' => $this->filter['updatedAtStart'] ?? '',
      'updatedAtEnd' => $this->filter['updatedAtEnd'] ?? '',
      'onlyTrashed' => $this->filter['onlyTrashed'] ?? 0,
    ];

    // Tipo de Pesquisa
    $this->filter['searchValueAndType'] = match ($this->filter['searchType']) {
        0 => $this->filter['searchValue'].'%',     // Inicio
        1 => '%'.$this->filter['searchValue'],     // Fim      
        2 => '%'.$this->filter['searchValue'].'%', // Qualquer Parte
        3 => $this->filter['searchValue'],         // Igual
      };    

    // Ordenação
    foreach ($this->filter['orderBy'] as $key => $value) {
      if (!$value) {
        continue;
      }
      // Ordenação Default
      $orderByColumn = trim(str_replace(' asc', '', $value));
      $orderByDirection = 'asc';

      // Ordenação Contrária
      if (str_contains($value, ' desc')) {
        $orderByColumn = trim(str_replace(' desc', '', $value));
        $orderByDirection = 'desc';
      }
      $this->filter['orderBy'][$key] = [
        'column' => $orderByColumn,
        'direction' => $orderByDirection
      ];
    }
  }
}

