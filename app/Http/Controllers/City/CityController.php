<?php

namespace App\Http\Controllers\City;

use App\Http\Controllers\Controller;
use App\Http\Services\City\CityIndexService;
use App\Http\Services\City\CityShowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CityController extends Controller
{
  public function index(Request $request): JsonResponse
  {
    try {
      $data = CityIndexService::make(
        $request->input('pageOption') ?? [],
        $request->input('filter') ?? [],
      )->execute();
    } catch (\Exception $ex) {
      return $this->responseError($ex->getMessage());
    }
    return $this->responseSuccess($data);
  }

  public function show(int $id): JsonResponse
  {
    try {
      $CityDto = CityShowService::make($id)->execute();
    } catch (\Exception $ex) {
    return $this->responseError($ex->getMessage(), Response::HTTP_NOT_FOUND);
    }
    return $this->responseSuccess($CityDto->toArray());
  }
}
