<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    // public function destroy(int $id): JsonResponse
    // {
    //     $this->tenantService->destroy($id);
    //     return $this->responseSuccess(code: Response::HTTP_NO_CONTENT);
    // }

    public function index(Request $request): JsonResponse
    {
        return $this->responseSuccess(
            User::all()
        );
    }

    // public function show(int $id): JsonResponse
    // {
    //     return $this->responseSuccess(
    //         $this->tenantService->show($id)
    //     );
    // }

    // public function store(Request $request): JsonResponse
    // {
    //     return $this->responseSuccess(
    //         $this->tenantService->store($tenantDto)->toArray(),
    //         Response::HTTP_CREATED
    //     );
    // }

    // public function update(Request $request, int $id): JsonResponse
    // {
    //     return $this->responseSuccess(
    //         $this->tenantService->update($id, $tenantDto)
    //     );
    // }
}
