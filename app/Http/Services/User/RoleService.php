<?php

namespace App\Http\Services\User;

use App\Http\Dto\User\RoleDto;
use App\Http\Repositories\User\RoleRepository;

class RoleService
{
  public function __construct(
    protected RoleRepository $roleRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(RoleRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->roleRepository->destroy($id);
  }

  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    return $this->roleRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): RoleDto
  {
    return $this->roleRepository->show($id);
  }

  public function store(RoleDto $dto): RoleDto
  {
    return $this->roleRepository->setTransaction(true)->store($dto);
  }

  public function update(int $id, RoleDto $dto): RoleDto
  {
    return $this->roleRepository->setTransaction(true)->update($id, $dto);
  }
}
