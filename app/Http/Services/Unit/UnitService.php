<?php

namespace App\Http\Services\Unit;

use App\Http\Dto\Unit\UnitDto;
use App\Http\Repositories\Unit\UnitRepository;

class UnitService
{
  public function __construct(
    protected UnitRepository $unitRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(UnitRepository::make());
  }

  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    return $this->unitRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): UnitDto
  {
    return $this->unitRepository->show($id);
  }
}