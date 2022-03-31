<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Dto\Company\CompanyDto;
use App\Http\Requests\Company\CompanyRequest;
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
  public function destroy(int $companyId): JsonResponse
  {
    try {
      CompanyDestroyService::make($companyId)->execute();
    } catch (\Exception $ex) {
      return $this->responseError($ex->getMessage(), Response::HTTP_NOT_FOUND);
    }
    return $this->responseSuccess([], Response::HTTP_NO_CONTENT);
  }

  public function index(Request $request): JsonResponse
  {
    $queryParams = $this->queryParamsValidated($request);
    try {
      $data = CompanyIndexService::make(
        $queryParams['pageOptions'],
        $queryParams['filter'],
      )->execute();
    } catch (\Exception $ex) {
      return $this->responseError($ex->getMessage());
    }
    return $this->responseSuccess($data);
  }

  public function show(int $companyId): JsonResponse
  {
    try {
      $data = CompanyShowService::make($companyId)->execute();
    } catch (\Exception $ex) {
    return $this->responseError($ex->getMessage(), Response::HTTP_NOT_FOUND);
    }
    return $this->responseSuccess($data);
  }

  // public function store(CompanyRequest $request): JsonResponse
  // {
  //   $companyDto = CompanyDto::make()->fromArray(
  //     $request->validated()
  //   );
  //   // dd($companyDto);    

  //   // try {
  //   //   $data = CompanyStoreService::make($companyData)->execute();
  //   // } catch (\Exception $ex) {
  //   //   return $this->responseError($ex->getMessage());
  //   // }
  //   return $this->responseSuccess($data, Response::HTTP_CREATED);
  // }

  public function store(CompanyRequest $request): JsonResponse
  {
    $companyData = $request->validated();

    try {
      $data = CompanyStoreService::make($companyData)->execute();
    } catch (\Exception $ex) {
      return $this->responseError($ex->getMessage());
    }
    return $this->responseSuccess($data, Response::HTTP_CREATED);
  }

  public function update(Request $request, int $companyId): JsonResponse
  {
    try {
      $companyData = $request->all();
      $data = CompanyUpdateService::make($companyId, $companyData)->execute();
    } catch (\Exception $ex) {
      return $this->responseError($ex->getMessage(), Response::HTTP_NOT_FOUND);
    }
    return $this->responseSuccess($data);
  }  
}
