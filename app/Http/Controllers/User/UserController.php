<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
/**
 * TODO-Falta implementar usuário corretamente
 */
class UserController extends Controller
{
    public function destroy(int $id): JsonResponse
    {
        return $this->responseSuccess('Recurso não implementado!');
    }

    public function index(Request $request): JsonResponse
    {
        $model = new User();
        return $this->responseSuccess(
            $model->with('role')->get()
        );
    }

    public function show(int $id): JsonResponse
    {
        return $this->responseSuccess(
            User::whereId($id)->with('role.rolePermission')->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        return $this->responseSuccess('Recurso não implementado!');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return $this->responseSuccess('Recurso não implementado!');
    }
}
