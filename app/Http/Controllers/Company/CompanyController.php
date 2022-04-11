<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Dto\Company\CompanyDto;
use App\Http\Services\Company\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
  public function __construct( 
    protected CompanyService $companyService
  ) {
  }

  public function destroy(int $id): JsonResponse
  {
    $this->companyService->destroy($id);
    return $this->responseSuccess(code: Response::HTTP_NO_CONTENT);
  }

  public function index(Request $request): JsonResponse
  {
    return $this->responseSuccess(
      $this->companyService->index(
        $request->input('page') ?? [],
        $request->input('filter') ?? [],
      )
    );
  }

  public function show(int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->companyService->show($id)
    );
  }

  public function store(CompanyDto $companyDto): JsonResponse
  {
    return $this->responseSuccess(
      $this->companyService->store($companyDto)->toArray(), 
      Response::HTTP_CREATED
    );
  }

  public function update(CompanyDto $companyDto, int $id): JsonResponse
  {
    return $this->responseSuccess(
      $this->companyService->update($id, $companyDto)
    );
  }
}