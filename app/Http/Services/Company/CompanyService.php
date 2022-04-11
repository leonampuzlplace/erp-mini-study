<?php

namespace App\Http\Services\Company;

use App\Http\Dto\Company\CompanyDto;
use App\Http\Repositories\Company\CompanyRepository;
use Spatie\LaravelData\Data;

class CompanyService
{
  public function __construct(
    protected CompanyRepository $companyRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(CompanyRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->companyRepository->destroy($id);
  }

  public function index(array $page = [], array $filter = [], array $filterEx = []): array
  {
    return $this->companyRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): CompanyDto
  {
    return $this->companyRepository->show($id);
  }

  public function store(CompanyDto $dto): CompanyDto
  {
    return $this->companyRepository->setWithTransaction(true)->store($dto);
  }

  public function update(int $id, CompanyDto $dto): CompanyDto
  {
    return $this->companyRepository->update($id, $dto);
  }
}
