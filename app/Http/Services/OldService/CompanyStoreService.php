<?php

namespace App\Http\Services\OldService\Company;

use App\Http\Dto\Company\CompanyDto;
use App\Http\Repositories\Company\CompanyRepository;

class CompanyStoreService
{
  private CompanyDto $companyDto;
  private CompanyRepository $companyRepository;

  private function __construct(CompanyRepository $companyRepository, CompanyDto $companyDto)
  {
    $this->companyRepository = $companyRepository;
    $this->companyDto = $companyDto;
  }

  public static function make(CompanyDto $companyDto): Self
  {
    return new self(CompanyRepository::make(), $companyDto);
  }

  public function execute(): CompanyDto
  {
    return $this->companyRepository->setWithTransaction(true)->store($this->companyDto);
  }
}