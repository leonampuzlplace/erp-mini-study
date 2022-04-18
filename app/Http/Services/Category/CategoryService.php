<?php

namespace App\Http\Services\Category;

use App\Http\Dto\Category\CategoryDto;
use App\Http\Repositories\Category\CategoryRepository;

class CategoryService
{
  public function __construct(
    protected CategoryRepository $categoryRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(CategoryRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->categoryRepository->destroy($id);
  }

  public function index(array $page = [], array $filter = [], array $filterEx = []): array
  {
    return $this->categoryRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): CategoryDto
  {
    return $this->categoryRepository->show($id);
  }

  public function store(CategoryDto $dto): CategoryDto
  {
    return $this->categoryRepository->setWithTransaction(true)->store($dto);
  }

  public function update(int $id, CategoryDto $dto): CategoryDto
  {
    return $this->categoryRepository->update($id, $dto);
  }
}
