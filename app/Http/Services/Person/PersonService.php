<?php

namespace App\Http\Services\Person;

use App\Http\Dto\Person\PersonDto;
use App\Http\Repositories\Person\PersonRepository;

class PersonService
{
  public function __construct(
    protected PersonRepository $personRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(PersonRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->personRepository->destroy($id);
  }

  public function index(array $page = [], array $filter = [], array $filterEx = []): array
  {
    return $this->personRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): PersonDto
  {
    return $this->personRepository->show($id);
  }

  public function store(PersonDto $dto): PersonDto
  {
    return $this->personRepository->setTransaction(true)->store($dto);
  }

  public function update(int $id, PersonDto $dto): PersonDto
  {
    return $this->personRepository->setTransaction(true)->update($id, $dto);
  }
}
