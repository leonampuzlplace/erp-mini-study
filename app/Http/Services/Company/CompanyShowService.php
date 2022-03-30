<?php

namespace App\Http\Services\Company;

use App\Http\Repositories\Company\CompanyRepository;

class CompanyShowService
{
  private int $companyId;
  private CompanyRepository $companyRepository;

  private function __construct(CompanyRepository $companyRepository, int $companyId)
  {
    $this->companyRepository = $companyRepository;
    $this->companyId = $companyId;
  }

  public static function make(int $companyId): CompanyShowService
  {
    return new self(CompanyRepository::make(), $companyId);
  }

  public function execute()
  {
    return $this->companyRepository->show($this->companyId);
  }
}
