<?php

namespace App\Http\Services\State;

use App\Http\Dto\State\StateDto;
use App\Http\Repositories\State\StateRepository;

class StateService
{
  public function __construct(
    protected StateRepository $repository
  ) {
  }

  public static function make(): Self
  {
    return new self(StateRepository::make());
  }

  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    return $this->repository->index($page, $filter, $filterEx);
  }

  public function show(int $id): StateDto
  {
    return $this->repository->show($id);
  }
}
