<?php

namespace App\Http\Services\City;

use App\Http\Dto\City\CityDto;
use App\Http\Repositories\City\CityRepository;

class CityService
{
  public function __construct(
    protected CityRepository $cityRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(CityRepository::make());
  }

  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    return $this->cityRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): CityDto
  {
    return $this->cityRepository->show($id);
  }
}
