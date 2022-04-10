<?php

namespace App\Http\Services\State;

use App\Http\Dto\State\StateDto;
use App\Http\Repositories\State\StateRepository;

class StateService
{
  public function __construct(
    protected StateRepository $stateRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(StateRepository::make());
  }

  public function index(array $page = [], array $filter = [], array $filterEx = []): array
  {
    return $this->stateRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): StateDto
  {
    return $this->stateRepository->show($id);
  }
}
