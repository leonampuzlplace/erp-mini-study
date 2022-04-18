<?php

namespace App\Http\Services\Product;

use App\Http\Dto\Product\ProductDto;
use App\Http\Repositories\Product\ProductRepository;

class ProductService
{
  public function __construct(
    protected ProductRepository $productRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(ProductRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->productRepository->destroy($id);
  }

  public function index(array $page = [], array $filter = [], array $filterEx = []): array
  {
    return $this->productRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): ProductDto
  {
    return $this->productRepository->show($id);
  }

  public function store(ProductDto $dto): ProductDto
  {
    return $this->productRepository->setWithTransaction(true)->store($dto);
  }

  public function update(int $id, ProductDto $dto): ProductDto
  {
    return $this->productRepository->update($id, $dto);
  }
}
