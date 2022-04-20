<?php

namespace App\Http\Services\Brand;

use App\Http\Dto\Brand\BrandDto;
use App\Http\Repositories\Brand\BrandRepository;

class BrandService
{
  public function __construct(
    protected BrandRepository $brandRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(BrandRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->brandRepository->destroy($id);
  }

  public function index(array $page = [], array $filter = [], array $filterEx = []): array
  {
    return $this->brandRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): BrandDto
  {
    return $this->brandRepository->show($id);
  }

  public function store(BrandDto $dto): BrandDto
  {
    return $this->brandRepository->store($dto);
  }

  public function update(int $id, BrandDto $dto): BrandDto
  {
    return $this->brandRepository->update($id, $dto);
  }
}
