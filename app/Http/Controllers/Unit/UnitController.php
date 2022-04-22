<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Http\Services\Unit\UnitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
  public function __construct(
    protected UnitService $service
  ) {
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
}
