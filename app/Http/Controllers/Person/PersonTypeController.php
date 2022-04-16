<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use App\Http\Services\Person\PersonTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonTypeController extends Controller
{
  public function __construct(
    protected PersonTypeService $personTypeService
  ) {
  }

  public function index(Request $request): JsonResponse
  {
    return $this->responseSuccess(
      $this->personTypeService->index(
        $request->input('page') ?? [],
        $request->input('filter') ?? [],
      )
    );
  }

  public function show(int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->personTypeService->show($id)
    );
  }
}
