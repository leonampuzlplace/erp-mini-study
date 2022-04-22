<?php

namespace App\Http\Services\Unit;

use App\Http\Dto\Unit\UnitDto;
use App\Http\Repositories\Unit\UnitRepository;

class UnitService
{
  public function __construct(
    protected UnitRepository $repository
  ) {
  }

  public static function make(): Self
  {
    return new self(UnitRepository::make());
  }

  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    return $this->repository->index($page, $filter, $filterEx);
  }

  public function show(int $id): UnitDto
  {
    return $this->repository->show($id);
  }
}