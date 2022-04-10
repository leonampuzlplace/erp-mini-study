<?php

namespace App\Http\Controllers\State;

use App\Http\Controllers\Controller;
use App\Http\Services\State\StateIndexService;
use App\Http\Services\State\StateShowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StateController extends Controller
{
  public function index(Request $request): JsonResponse
  {
    try {
      $data = StateIndexService::make(
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
      $StateDto = StateShowService::make($id)->execute();
    } catch (\Exception $ex) {
    return $this->responseError($ex->getMessage(), Response::HTTP_NOT_FOUND);
    }
    return $this->responseSuccess($StateDto->toArray());
  }
}
