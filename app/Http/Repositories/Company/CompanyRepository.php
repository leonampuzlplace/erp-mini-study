<?php

namespace App\Http\Repositories\Company;

use App\Http\Repositories\BaseRepository;
use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;

class CompanyRepository extends BaseRepository
{
  private function __construct(Company $company)
  {
    parent::__construct($company);
  }

  public static function make(): CompanyRepository
  {
    return new self(new Company);
  }

  public function index(array $pageOptions = [], array $filter = []): array
  {
    $pageOptions = $this->pageOptionsValidated($pageOptions);
    $filter = $this->filterValidated($filter);
    $queryBuilder = $this->model->newQuery();

    // Ordenação
    foreach ($filter['orderBy'] as $value) {
      if ((!$value) || (!$value['column']) || (!$value['direction'])) {
        continue;
      }
      $queryBuilder->orderBy($value['column'], $value['direction']);        
    }

    // Filtragem
    if (($filter['searchField']) && ($filter['searchValue'])) {
      $queryBuilder->where($filter['searchField'], 'like', $filter['searchValueAndType']);
    }

    // Pesquisa Customizada
    foreach ($filter['customSearchValue'] as $value) {
      if (!$value) {
        continue;
      }      
      $value = "%${value}%";
      $queryBuilder->where(function ($query) use ($value) {
        return $query->where('company.business_name', 'like', $value)
          ->orWhere('company.alias_name', 'like', $value)
          ->orWhere('company.company_ein', 'like', $value);
      });
    }

    // Paginação
    $queryBuilder = match ($pageOptions['paginateType']){
      0 => $queryBuilder->paginate($pageOptions['perPage'], $pageOptions['columns'], 'page', $pageOptions['page']),
      1 => $queryBuilder->simplePaginate($pageOptions['perPage'], $pageOptions['columns'], 'page', $pageOptions['page']),
      2 => $queryBuilder->cursorPaginate($pageOptions['perPage'], $pageOptions['columns'], 'cursor', $pageOptions['cursor']),
    };

    // Resultado
    return ($pageOptions['onlyData'] == 1)
      ? $queryBuilder->toArray()['data'] 
      : $queryBuilder->toArray();    
  }  
}
