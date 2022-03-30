<?php

namespace App\Http\Services\Company;

use App\Http\Repositories\Company\CompanyRepository;

class CompanyUpdateService
{
  private int $companyId;
  private array $companyData;
  private CompanyRepository $companyRepository;

  private function __construct(CompanyRepository $companyRepository, int $companyId, array $companyData)
  {
    $this->companyRepository = $companyRepository;
    $this->companyId = $companyId;
    $this->companyData = $companyData;
  }

  public static function make(int $companyId, array $companyData): CompanyUpdateService
  {
    $companyService = new self(CompanyRepository::make(), $companyId, $companyData);
    return $companyService;
  }

  public function execute(): array
  {
    return $this->companyRepository->update($this->companyId, $this->companyData);
  }
}
