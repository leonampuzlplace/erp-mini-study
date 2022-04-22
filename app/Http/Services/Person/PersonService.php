<?php

namespace App\Http\Services\Person;

use App\Http\Dto\Person\PersonDto;
use App\Http\Repositories\Person\PersonRepository;
use App\Http\Services\User\RoleService;

class PersonService
{
  public function __construct(
    protected PersonRepository $repository
  ) {
  }

  public static function make(): Self
  {
    return new self(PersonRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->repository->destroy($id);
  }

  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    return $this->repository->index($page, $filter, $filterEx);
  }

  public function show(int $id): PersonDto
  {
    return $this->repository->show($id);
  }

  public function store(PersonDto $dto): PersonDto
  {
    return $this->repository->setTransaction(true)->store($dto);
  }

  public function update(int $id, PersonDto $dto): PersonDto
  {
    return $this->repository->setTransaction(true)->update($id, $dto);
  }

  public static function permissionTemplate(): array
  {
    return RoleService::permissionTemplateDefault('person', 'Pessoas');
  }  
}