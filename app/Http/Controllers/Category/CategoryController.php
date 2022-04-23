<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Dto\Category\CategoryDto;
use App\Http\Services\Category\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
  public function __construct(
    protected CategoryService $service
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

  public function store(CategoryDto $categoryDto): JsonResponse
  {
    return $this->responseSuccess(
      $this->service->store($categoryDto),
      Response::HTTP_CREATED
    );
  }

  public function update(CategoryDto $categoryDto, int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->service->update($id, $categoryDto)
    );
  }
}
