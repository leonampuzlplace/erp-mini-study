<?php

namespace App\Http\Services\City;

use App\Http\Dto\City\CityDto;
use App\Http\Repositories\City\CityRepository;

class CityShowService
{
  private int $id;
  private CityRepository $cityRepository;

  private function __construct(CityRepository $cityRepository, int $id)
  {
    $this->cityRepository = $cityRepository;
    $this->id = $id;
  }

  public static function make(int $id): Self
  {
    return new self(CityRepository::make(), $id);
  }

  public function execute(): CityDto
  {
    return $this->cityRepository->show($this->id);
  }
}
