<?php

namespace App\Http\Controllers\City;

use App\Http\Controllers\Controller;
use App\Http\Services\City\CityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
  public function __construct(
    protected CityService $cityService
  ) {
  }

  public function index(Request $request): JsonResponse
  {
    return $this->responseSuccess(
      $this->cityService->index(
        $request->input('page') ?? [],
        $request->input('filter') ?? [],
      )
    );
  }

  public function show(int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->cityService->show($id)
    );
  }
}
