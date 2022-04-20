<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use App\Http\Dto\Person\PersonDto;
use App\Http\Services\Person\PersonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PersonController extends Controller
{
  public function __construct(
    protected PersonService $personService
  ) {
  }

  public function destroy(int $id): JsonResponse
  {
    $this->personService->destroy($id);
    return $this->responseSuccess(code: Response::HTTP_NO_CONTENT);
  }

  public function index(Request $request): JsonResponse
  {
    return $this->responseSuccess(
      $this->personService->index(
        $request->input('page') ?? [],
        $request->input('filter') ?? [],
        $request->input('filterEx') ?? [],
      )
    );
  }

  public function show(int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->personService->show($id)
    );
  }

  public function store(PersonDto $personDto): JsonResponse
  {
    return $this->responseSuccess(
      $this->personService->store($personDto)->toArray(),
      Response::HTTP_CREATED
    );
  }

  public function update(PersonDto $personDto, int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->personService->update($id, $personDto)
    );
  }
}
