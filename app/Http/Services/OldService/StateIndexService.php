<?php

namespace App\Http\Services\State;

use App\Http\Repositories\State\StateRepository;

class StateIndexService
{
  private StateRepository $stateRepository;
  private array $pageOption;
  private array $filter;

  private function __construct(StateRepository $stateRepository, array $pageOption = [], array $filter = [])
  {
    $this->stateRepository = $stateRepository;
    $this->pageOption = $pageOption;
    $this->filter = $filter;
  }

  public static function make(array $pageOption = [], array $filter = []): Self
  {
    return new self(
      StateRepository::make(),
      $pageOption,
      $filter,
    );  
  }

  public function execute(): array
  {
    return $this->stateRepository->index($this->pageOption, $this->filter);
  }
}