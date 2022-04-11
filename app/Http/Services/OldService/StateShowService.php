<?php

namespace App\Http\Services\State;

use App\Http\Dto\State\StateDto;
use App\Http\Repositories\State\StateRepository;

class StateShowService
{
  private int $id;
  private StateRepository $stateRepository;

  private function __construct(StateRepository $stateRepository, int $id)
  {
    $this->stateRepository = $stateRepository;
    $this->id = $id;
  }

  public static function make(int $id): Self
  {
    return new self(StateRepository::make(), $id);
  }

  public function execute(): StateDto
  {
    return $this->stateRepository->show($this->id);
  }
}
