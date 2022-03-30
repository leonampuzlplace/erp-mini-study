<?php

namespace App\Http\Services\Company;

use App\Http\Repositories\Company\CompanyRepository;

class CompanyStoreService
{
  private array $companyData;
  private CompanyRepository $companyRepository;

  private function __construct(CompanyRepository $companyRepository, array $companyData)
  {
    $this->companyRepository = $companyRepository;
    $this->companyData = $companyData;
  }

  public static function make(array $companyData): CompanyStoreService
  {
    return new self(CompanyRepository::make(), $companyData);
  }

  public function execute(): array
  {
    return $this->companyRepository->store($this->companyData);
  }
}