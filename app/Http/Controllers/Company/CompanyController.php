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
    try {
      CompanyDestroyService::make($id)->execute();
    } catch (\Exception $ex) {
      return $this->responseError($ex->getMessage(), Response::HTTP_NOT_FOUND);
    }
    return $this->responseSuccess([], Response::HTTP_NO_CONTENT);
  }

  public function index(Request $request): JsonResponse
  {
    try {
      $data = CompanyIndexService::make(
        $request->input('pageOption') ?? [],
        $request->input('filter') ?? [],
      )->execute();
    } catch (\Exception $ex) {
      return $this->responseError($ex->getMessage());
    }
    return $this->responseSuccess($data);
  }

  public function show(int $id): JsonResponse
  {
    try {
      $companyDto = CompanyShowService::make($id)->execute();
    } catch (\Exception $ex) {
    return $this->responseError($ex->getMessage(), Response::HTTP_NOT_FOUND);
    }
    return $this->responseSuccess($companyDto->toArray());
  }

  public function store(CompanyDto $companyDto): JsonResponse
  {
    try {
      $companyDto = CompanyStoreService::make($companyDto)->execute();
    } catch (\Exception $ex) {
      return $this->responseError($ex->getMessage());
    }
    return $this->responseSuccess($companyDto->toArray(), Response::HTTP_CREATED);
  }

  public function update(CompanyDto $companyDto, int $id): JsonResponse
  {
    try {
      $companyDto = CompanyUpdateService::make($id, $companyDto)->execute();
    } catch (\Exception $ex) {
      return $this->responseError($ex->getMessage(), Response::HTTP_NOT_FOUND);
    }
    return $this->responseSuccess($companyDto->toArray());
  }
}
