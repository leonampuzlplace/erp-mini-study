<?php

namespace App\Http\Services\Stock;

use App\Http\Dto\Stock\StockDto;
use App\Http\Repositories\Stock\StockRepository;
use App\Http\Services\User\RoleService;

class StockService
{
  public function __construct(
    protected StockRepository $repository
  ) {
  }

  public static function make(): Self
  {
    return new self(StockRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->repository->destroy($id);
  }

  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    return $this->repository->index($page, $filter, $filterEx);
  }

  public function show(int $id): StockDto
  {
    return $this->repository->show($id);
  }

  public function store(StockDto $dto): StockDto
  {
    return $this->repository->setTransaction(false)->store($dto);
  }

  public function update(int $id, StockDto $dto): StockDto
  {
    return $this->repository->setTransaction(false)->update($id, $dto);
  }

  public static function permissionTemplate(): array
  {
    return RoleService::permissionTemplateDefault('stock', 'Produtos / Serviços');
  }  
}