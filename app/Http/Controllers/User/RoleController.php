<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Dto\User\RoleDto;
use App\Http\Middleware\ACL;
use App\Http\Services\User\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    /**
    * Constructor
    *
    * @param RoleService $roleService
    */
    public function __construct(
        protected RoleService $roleService
    ) {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->roleService->destroy($id);
        return $this->responseSuccess(code: Response::HTTP_NO_CONTENT);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->responseSuccess(
        $this->roleService->index(
            $request->input('page'),
            $request->input('filter'),
        )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->responseSuccess(
            $this->roleService->show($id)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RoleDto $roleDto
     * @return \Illuminate\Http\Response
     */
    public function store(RoleDto $roleDto): JsonResponse
    {
        return $this->responseSuccess(
            $this->roleService->store($roleDto)->toArray(),
            Response::HTTP_CREATED
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RoleDto $roleDto
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(RoleDto $roleDto, int $id): JsonResponse
    {
        return $this->responseSuccess(
            $this->roleService->update($id, $roleDto)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function permissionTemplate(): JsonResponse
    {
        return $this->responseSuccess(
            $this->roleService->permissionTemplate()
        );
    }    
}
