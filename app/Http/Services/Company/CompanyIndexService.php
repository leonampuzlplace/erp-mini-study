<?php

namespace App\Http\Services\Company;

use App\Http\Repositories\Company\CompanyRepository;

class CompanyIndexService
{
  private CompanyRepository $companyRepository;
  private array $paginateOptions;
  private array $filter;

  private function __construct(CompanyRepository $companyRepository, array $paginateOptions = [], array $filter = [])
  {
    $this->companyRepository = $companyRepository;
    $this->paginateOptions = $paginateOptions;
    $this->filter = $filter;
  }

  public static function make(array $paginateOptions = [], array $filter = []): CompanyIndexService
  {
    return new self(
      CompanyRepository::make(), 
      $paginateOptions, 
      $filter
    );  
  }

  public function execute(): array
  {
    return $this->companyRepository->index($this->paginateOptions, $this->filter);
  }
}