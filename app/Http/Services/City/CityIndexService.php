<?php

namespace App\Http\Services\City;

use App\Http\Repositories\City\CityRepository;

class CityIndexService
{
  private CityRepository $cityRepository;
  private array $pageOption;
  private array $filter;

  private function __construct(CityRepository $cityRepository, array $pageOption = [], array $filter = [])
  {
    $this->cityRepository = $cityRepository;
    $this->pageOption = $pageOption;
    $this->filter = $filter;
  }

  public static function make(array $pageOption = [], array $filter = []): Self
  {
    return new self(
      CityRepository::make(),
      $pageOption,
      $filter,
    );  
  }

  public function execute(): array
  {
    return $this->cityRepository->index($this->pageOption, $this->filter);
  }
}