<?php

namespace App\Http\Services\Tenant;

use App\Http\Dto\Tenant\TenantDto;
use App\Http\Repositories\Tenant\TenantRepository;

class TenantService
{
  public function __construct(
    protected TenantRepository $repository
  ) {
  }

  public static function make(): Self
  {
    return new self(TenantRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->repository->destroy($id);
  }

  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    return $this->repository->index($page, $filter, $filterEx);
  }

  public function show(int $id): TenantDto
  {
    return $this->repository->show($id);
  }

  public function store(TenantDto $dto): TenantDto
  {
    return $this->repository->setTransaction(true)->store($dto);
  }

  public function update(int $id, TenantDto $dto): TenantDto
  {
    return $this->repository->setTransaction(true)->update($id, $dto);
  }
}
