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
  /**
   * Undocumented function
   *
   * @param BrandService $service
   */
  public function __construct(
    protected BrandService $service
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

  public function store(BrandDto $brandDto): JsonResponse
  {
    return $this->responseSuccess(
      $this->service->store($brandDto),
      Response::HTTP_CREATED
    );
  }

  /**
   * Undocumented function
   *
   * @param BrandDto $brandDto
   * @param integer $id
   * @return JsonResponse
   */
  public function update(BrandDto $brandDto, int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->service->update($id, $brandDto)
    );
  }
}
