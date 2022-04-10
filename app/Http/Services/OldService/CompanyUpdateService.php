<?php

namespace App\Http\Services\OldService\Company;

use App\Http\Dto\Company\CompanyDto;
use App\Http\Repositories\Company\CompanyRepository;

class CompanyUpdateService
{
  private int $id;
  private CompanyDto $companyDto;
  private CompanyRepository $companyRepository;

  private function __construct(CompanyRepository $companyRepository, int $id, CompanyDto $companyDto)
  {
    $this->companyRepository = $companyRepository;
    $this->id = $id;
    $this->companyDto = $companyDto;
  }

  public static function make(int $id, CompanyDto $companyDto): Self
  {
    $companyService = new self(CompanyRepository::make(), $id, $companyDto);
    return $companyService;
  }

  public function execute(): CompanyDto
  {
    return $this->companyRepository->update($this->id, $this->companyDto);
  }
}
