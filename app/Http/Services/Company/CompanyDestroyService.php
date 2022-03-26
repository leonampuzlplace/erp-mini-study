<?php

namespace App\Http\Services\Company;

use App\Http\Repositories\Company\CompanyRepository;

class CompanyDestroyService
{
  private int $companyId;
  private CompanyRepository $companyRepository;

  public static function make(int $companyId): CompanyDestroyService
  {
    $companyService = new self;
    $companyService->companyRepository = CompanyRepository::make();
    $companyService->companyId = $companyId;
    return $companyService;
  }

  public function execute(): bool
  {
    return $this->companyRepository->destroy($this->companyId);
  }
}