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
    protected CategoryService $categoryService
  ) {
  }

  public function destroy(int $id): JsonResponse
  {
    $this->categoryService->destroy($id);
    return $this->responseSuccess(code: Response::HTTP_NO_CONTENT);
  }

  public function index(Request $request): JsonResponse
  {
    return $this->responseSuccess(
      $this->categoryService->index(
        $request->input('page'),
        $request->input('filter'),
      )
    );
  }

  public function show(int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->categoryService->show($id)
    );
  }

  public function store(CategoryDto $categoryDto): JsonResponse
  {
    return $this->responseSuccess(
      $this->categoryService->store($categoryDto)->toArray(),
      Response::HTTP_CREATED
    );
  }

  public function update(CategoryDto $categoryDto, int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->categoryService->update($id, $categoryDto)
    );
  }
}
