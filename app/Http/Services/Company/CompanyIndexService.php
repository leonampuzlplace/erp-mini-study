<?php

namespace App\Http\Services\Company;

use App\Http\Repositories\Company\CompanyRepository;

class CompanyIndexService
{
  private CompanyRepository $companyRepository;
  private array $paginateOptions;
  private array $filter;

  public static function make(array $paginateOptions = [], array $filter = []): CompanyIndexService
  {
    $companyService = new self;
    $companyService->companyRepository = CompanyRepository::make();
    $companyService->paginateOptions = $paginateOptions;
    $companyService->filter = $filter;
    return $companyService;
  }

  public function execute(): array
  {
    return $this->companyRepository->index($this->paginateOptions, $this->filter);
  }
}