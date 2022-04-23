<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Dto\Tenant\TenantDto;
use App\Http\Services\Tenant\TenantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TenantController extends Controller
{
  public function __construct( 
    protected TenantService $service
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

  public function store(TenantDto $tenantDto): JsonResponse
  {
    return $this->responseSuccess(
      $this->service->store($tenantDto), 
      Response::HTTP_CREATED
    );
  }

  public function update(TenantDto $tenantDto, int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->service->update($id, $tenantDto)
    );
  }
}