<?php

namespace App\Http\Services\Brand;

use App\Http\Dto\Brand\BrandDto;
use App\Http\Repositories\Brand\BrandRepository;
use App\Http\Services\User\RoleService;

class BrandService
{
  public function __construct(
    protected BrandRepository $repository
  ) {
  }

  public static function make(): Self
  {
    return new self(BrandRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->repository->destroy($id);
  }

  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    return $this->repository->index($page, $filter, $filterEx);
  }

  public function show(int $id): BrandDto
  {
    return $this->repository->show($id);
  }

  public function store(BrandDto $dto): BrandDto
  {
    return $this->repository->setTransaction(false)->store($dto);
  }

  public function update(int $id, BrandDto $dto): BrandDto
  {
    return $this->repository->setTransaction(false)->update($id, $dto);
  }

  public static function permissionTemplate(): array
  {
    return RoleService::permissionTemplateDefault('brand', 'Marcas');
  }
}
