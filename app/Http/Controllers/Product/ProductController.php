<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Dto\Product\ProductDto;
use App\Http\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
  public function __construct(
    protected ProductService $productService
  ) {
  }

  public function destroy(int $id): JsonResponse
  {
    $this->productService->destroy($id);
    return $this->responseSuccess(code: Response::HTTP_NO_CONTENT);
  }

  public function index(Request $request): JsonResponse
  {
    return $this->responseSuccess(
      $this->productService->index(
        $request->input('page') ?? [],
        $request->input('filter') ?? [],
      )
    );
  }

  public function show(int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->productService->show($id)
    );
  }

  public function store(ProductDto $productDto): JsonResponse
  {
    return $this->responseSuccess(
      $this->productService->store($productDto)->toArray(),
      Response::HTTP_CREATED
    );
  }

  public function update(ProductDto $productDto, int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->productService->update($id, $productDto)
    );
  }
}
