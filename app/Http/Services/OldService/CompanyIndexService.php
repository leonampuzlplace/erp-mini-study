<?php

namespace App\Http\Services\OldService\Company;

use App\Http\Repositories\Company\CompanyRepository;

class CompanyIndexService
{
  private CompanyRepository $companyRepository;
  private array $pageOption;
  private array $filter;
  private array $filterEx;

  private function __construct(CompanyRepository $companyRepository, array $pageOption = [], array $filter = [], array $filterEx = [])
  {
    $this->companyRepository = $companyRepository;
    $this->pageOption = $pageOption;
    $this->filter = $filter;
    $this->filterEx = $filterEx;
  }

  public static function make(array $pageOption = [], array $filter = [], array $filterEx = []): Self
  {
    return new self(
      CompanyRepository::make(),
      $pageOption,
      $filter,
      $filterEx,
    );  
  }

  public function execute(): array
  {
    return $this->companyRepository->index($this->pageOption, $this->filter, $this->filterEx);
  }
}