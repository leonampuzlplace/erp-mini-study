<?php

namespace App\Http\Controllers\City;

use App\Http\Controllers\Controller;
use App\Http\Services\City\CityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
  public function __construct(
    protected CityService $service
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
