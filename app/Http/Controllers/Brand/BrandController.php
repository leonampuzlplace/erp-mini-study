<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Http\Dto\Brand\BrandDto;
use App\Http\Services\Brand\BrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BrandController extends Controller
{
  public function __construct(
    protected BrandService $brandService
  ) {
  }

  public function destroy(int $id): JsonResponse
  {
    $this->brandService->destroy($id);
    return $this->responseSuccess(code: Response::HTTP_NO_CONTENT);
  }

  public function index(Request $request): JsonResponse
  {
    return $this->responseSuccess(
      $this->brandService->index(
        $request->input('page') ?? [],
        $request->input('filter') ?? [],
      )
    );
  }

  public function show(int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->brandService->show($id)
    );
  }

  public function store(BrandDto $brandDto): JsonResponse
  {
    return $this->responseSuccess(
      $this->brandService->store($brandDto)->toArray(),
      Response::HTTP_CREATED
    );
  }

  public function update(BrandDto $brandDto, int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->brandService->update($id, $brandDto)
    );
  }
}
