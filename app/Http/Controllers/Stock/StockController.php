<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Dto\Stock\StockDto;
use App\Http\Services\Stock\StockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StockController extends Controller
{
  public function __construct(
    protected StockService $service
  ) {
  }

  public function destroy(int $id): JsonResponse
  {
    $this->service->destroy($id);
    return $this->responseSuccess(code: Response::HTTP_NO_CONTENT);
  }

  public function index(Request $request): JsonResponse
  {
    return $this->responseSuccess(
      $this->service->index(
        $request->input('page'),
        $request->input('filter'),
      )
    );
  }

  public function show(int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->service->show($id)
    );
  }

  public function store(StockDto $stockDto): JsonResponse
  {
    return $this->responseSuccess(
      $this->service->store($stockDto),
      Response::HTTP_CREATED
    );
  }

  public function update(StockDto $stockDto, int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->service->update($id, $stockDto)
    );
  }
}