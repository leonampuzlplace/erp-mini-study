<?php

namespace App\Http\Repositories\Company;

use App\Http\Repositories\BaseRepository;
use App\Models\Company;

class CompanyRepository extends BaseRepository
{
  public function __construct()
  {
    parent::__construct(new Company());
  }

  public static function make(): CompanyRepository
  {
    $companyRepository = new self;
    return $companyRepository;
  }

  public function index(array $paginateOptions = [], array $filter = []): array
  {
    $paginateOptions = $this->paginateOptionsValidated($paginateOptions);
    $filter = $this->filterValidated($filter);
    
    // Iniciar construção da Query
    $queryBuilder = $this->model->newQuery();

    // Ordenação
    if ($filter['orderBy']) {
      $queryBuilder->orderByRaw($filter['orderBy']);
    };

    // Filtragem
    if (($filter['searchField']) && ($filter['searchValue'])) {
      $queryBuilder->where($filter['searchField'], 'like', $filter['searchValueAndType']);
    }

    // Pesquisa Customizada
    if ($filter['customSearchValue']) {
      foreach ($filter['customSearchValue'] as $value) {
        $value = "%${value}%";
        $queryBuilder->where(function ($query) use ($value) {
          return $query->where('company.business_name', 'like', $value)
            ->orWhere('company.alias_name', 'like', $value)
            ->orWhere('company.company_ein', 'like', $value);
        });
      }
    }

    // Paginação
    $queryBuilder = match ($paginateOptions['paginateType']){
      0 => $queryBuilder->paginate($paginateOptions['perPage'], $paginateOptions['columns'], 'page', $paginateOptions['page']),
      1 => $queryBuilder->simplePaginate($paginateOptions['perPage'], $paginateOptions['columns'], 'page', $paginateOptions['page']),
      2 => $queryBuilder->cursorPaginate($paginateOptions['perPage'], $paginateOptions['columns'], 'cursor', $paginateOptions['cursor']),
    };

    // Resultado
    return ($paginateOptions['onlyData'] == 1)
      ? $queryBuilder->toArray()['data'] 
      : $queryBuilder->toArray();    
  }  
}
