<?php

namespace App\Http\Services\Stock;

use App\Http\Dto\Stock\StockDto;
use App\Http\Repositories\Stock\StockRepository;

class StockService
{
  public function __construct(
    protected StockRepository $stockRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(StockRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->stockRepository->destroy($id);
  }

  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    return $this->stockRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): StockDto
  {
    return $this->stockRepository->show($id);
  }

  public function store(StockDto $dto): StockDto
  {
    return $this->stockRepository->setTransaction(false)->store($dto);
  }

  public function update(int $id, StockDto $dto): StockDto
  {
    return $this->stockRepository->setTransaction(false)->update($id, $dto);
  }
}
