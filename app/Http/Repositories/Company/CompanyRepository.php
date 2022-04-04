<?php

namespace App\Http\Repositories\Company;

use App\Http\Repositories\BaseRepository;
use App\Models\Company;

class CompanyRepository extends BaseRepository
{
  private function __construct(Company $company)
  {
    parent::__construct($company);
  }

  public static function make(): Self
  {
    return new self(new Company);
  }

  public function index(array $pageOptions = [], array $filter = []): array
  {
    $this->pageOptions = $pageOptions;
    $this->filter = $filter;
    $queryBuilder = $this->indexBuilder();

    // Adicionar algum join
    $queryBuilder->leftJoin('company_address', 'company_address.company_id', 'company.id');
    $selectRaw = 'company.*, company_address.zipcode, company_address.address, company_address.address_number';

    // Pesquisa Customizada
    foreach ($filter['customSearchValue'] as $value) {
      $value = "%${value}%";
      $queryBuilder->where(function ($query) use ($value) {
        return $query->where('company.business_name', 'like', $value)
          ->orWhere('company.alias_name', 'like', $value)
          ->orWhere('company.company_ein', 'like', $value);
      });
    }

    // Paginação e Retorno dos dados
    return $this->indexGetAndPaginate($queryBuilder, $selectRaw);
  }
}