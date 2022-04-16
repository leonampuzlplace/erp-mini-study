<?php

namespace App\Http\Services\Person;

use App\Http\Dto\Person\PersonTypeDto;
use App\Http\Repositories\Person\PersonTypeRepository;

class PersonTypeService
{
  public function __construct(
    protected PersonTypeRepository $personTypeRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(PersonTypeRepository::make());
  }

  public function index(array $page = [], array $filter = [], array $filterEx = []): array
  {
    return $this->personTypeRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): PersonTypeDto
  {
    return $this->personTypeRepository->show($id);
  }
}
