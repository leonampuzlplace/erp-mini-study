<?php

namespace App\Http\Services\Tenant;

use App\Http\Dto\Tenant\TenantDto;
use App\Http\Repositories\Tenant\TenantRepository;

class TenantService
{
  public function __construct(
    protected TenantRepository $tenantRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(TenantRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->tenantRepository->destroy($id);
  }

  public function index(array $page = [], array $filter = [], array $filterEx = []): array
  {
    return $this->tenantRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): TenantDto
  {
    return $this->tenantRepository->show($id);
  }

  public function store(TenantDto $dto): TenantDto
  {
    return $this->tenantRepository->setWithTransaction(true)->store($dto);
  }

  public function update(int $id, TenantDto $dto): TenantDto
  {
    return $this->tenantRepository->update($id, $dto);
  }
}
