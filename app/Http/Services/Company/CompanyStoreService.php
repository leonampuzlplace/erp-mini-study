<?php

namespace App\Http\Services\Company;

use App\Http\Repositories\Company\CompanyRepository;

class CompanyStoreService
{
  private array $companyData;
  private CompanyRepository $companyRepository;
  
  public static function make(array $companyData): CompanyStoreService
  {
    $companyService = new self;
    $companyService->companyRepository = CompanyRepository::make();
    $companyService->companyData = $companyData;
    return $companyService;
  }

  public function execute(): array
  {
    return $this->companyRepository->store($this->companyData);
  }
}
