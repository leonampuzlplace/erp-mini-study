<?php

namespace App\Http\Services\Company;

use App\Http\Repositories\Company\CompanyRepository;

class CompanyUpdateService
{
  private int $companyId;
  private array $companyData;
  private CompanyRepository $companyRepository;
  
  public static function make(int $companyId, array $companyData): CompanyUpdateService
  {
    $companyService = new self;
    $companyService->companyRepository = CompanyRepository::make();
    $companyService->companyId = $companyId;
    $companyService->companyData = $companyData;
    return $companyService;
  }

  public function execute(): array
  {
    return $this->companyRepository->update($this->companyId, $this->companyData);
  }
}
