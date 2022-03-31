<?php

namespace App\Http\Services\Company;

use App\Http\Repositories\Company\CompanyRepository;

class CompanyIndexService
{
  private CompanyRepository $companyRepository;
  private array $pageOptions;
  private array $filter;

  private function __construct(CompanyRepository $companyRepository, array $pageOptions = [], array $filter = [])
  {
    $this->companyRepository = $companyRepository;
    $this->pageOptions = $pageOptions;
    $this->filter = $filter;
  }

  public static function make(array $pageOptions = [], array $filter = []): CompanyIndexService
  {
    return new self(
      CompanyRepository::make(),
      $pageOptions,
      $filter
    );  
  }

  public function execute(): array
  {
    return $this->companyRepository->index($this->pageOptions, $this->filter);
  }
}