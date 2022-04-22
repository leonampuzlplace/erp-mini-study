<?php

namespace App\Http\Services\Category;

use App\Http\Dto\Category\CategoryDto;
use App\Http\Repositories\Category\CategoryRepository;
use App\Http\Services\User\RoleService;

class CategoryService
{
  public function __construct(
    protected CategoryRepository $repository
  ) {
  }

  public static function make(): Self
  {
    return new self(CategoryRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->repository->destroy($id);
  }

  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    return $this->repository->index($page, $filter, $filterEx);
  }

  public function show(int $id): CategoryDto
  {
    return $this->repository->show($id);
  }

  public function store(CategoryDto $dto): CategoryDto
  {
    return $this->repository->setTransaction(false)->store($dto);
  }

  public function update(int $id, CategoryDto $dto): CategoryDto
  {
    return $this->repository->setTransaction(false)->update($id, $dto);
  }

  public static function permissionTemplate(): array
  {
    return RoleService::permissionTemplateDefault('category', 'Categorias');
  }  
}
