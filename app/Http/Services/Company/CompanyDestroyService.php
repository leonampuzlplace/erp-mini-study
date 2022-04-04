<?php

namespace App\Http\Services\Company;

use App\Http\Repositories\Company\CompanyRepository;

class CompanyDestroyService
{
  private int $id;
  private CompanyRepository $companyRepository;

  private function __construct(CompanyRepository $companyRepository, int $id)
  {
    $this->companyRepository = $companyRepository;
    $this->id = $id;
  }

  public static function make(int $id): Self
  {
    return new self(CompanyRepository::make(), $id);
  }

  public function execute(): bool
  {
    return $this->companyRepository->destroy($this->id);
  }
}