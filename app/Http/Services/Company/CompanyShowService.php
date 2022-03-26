<?php

namespace App\Http\Services\Company;

use App\Http\Repositories\Company\CompanyRepository;

class CompanyShowService
{
  private int $companyId;
  private CompanyRepository $companyRepository;
  
  public static function make(int $companyId): CompanyShowService
  {
    $companyService = new self;
    $companyService->companyRepository = CompanyRepository::make();
    $companyService->companyId = $companyId;
    return $companyService;
  }

  public function execute()
  {
    return $this->companyRepository->show($this->companyId);
  }
}
