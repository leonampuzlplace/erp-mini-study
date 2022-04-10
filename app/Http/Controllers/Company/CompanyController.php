<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Dto\Company\CompanyDto;
use App\Http\Services\Company\CompanyDestroyService;
use App\Http\Services\Company\CompanyIndexService;
use App\Http\Services\Company\CompanyShowService;
use App\Http\Services\Company\CompanyStoreService;
use App\Http\Services\Company\CompanyUpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
  public function destroy(int $id): JsonResponse
  {
    CompanyDestroyService::make($id)->execute();
    return $this->responseSuccess([], Response::HTTP_NO_CONTENT);
  }

  public function index(Request $request): JsonResponse
  {
    $data = CompanyIndexService::make(
      $request->input('pageOption') ?? [],
      $request->input('filter') ?? [],
    )->execute();
   
    return $this->responseSuccess($data);
  }

  public function show(int $id): JsonResponse
  {
    $companyDto = CompanyShowService::make($id)->execute();
    return $this->responseSuccess($companyDto->toArray());
  }

  public function store(CompanyDto $companyDto): JsonResponse
  {
    $companyDto = CompanyStoreService::make($companyDto)->execute();
    return $this->responseSuccess($companyDto->toArray(), Response::HTTP_CREATED);
  }

  public function update(CompanyDto $companyDto, int $id): JsonResponse
  {
    $companyDto = CompanyUpdateService::make($id, $companyDto)->execute();
    return $this->responseSuccess($companyDto->toArray());
  }
}