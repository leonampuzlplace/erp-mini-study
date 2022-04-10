<?php

namespace App\Http\Controllers\State;

use App\Http\Controllers\Controller;
use App\Http\Services\State\StateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StateController extends Controller
{
  public function __construct(
    protected StateService $stateService
  ) {
  }

  public function index(Request $request): JsonResponse
  {
    return $this->responseSuccess(
      $this->stateService->index(
        $request->input('page') ?? [],
        $request->input('filter') ?? [],
      )
    );
  }

  public function show(int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->stateService->show($id)
    );
  }
}
