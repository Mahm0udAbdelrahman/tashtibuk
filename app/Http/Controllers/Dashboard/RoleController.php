<?php
namespace App\Http\Controllers\Dashboard;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\RoleService;
use App\Http\Resources\Dashboard\RoleResource;
use App\Http\Resources\Dashboard\PermissionsResource;


class RoleController extends Controller
{
    use HttpResponse;

    public function __construct(public RoleService $roleService)
    {}
    public function index()
    {
        $roles = $this->roleService->index();
        return $this->paginatedResponse($roles, RoleResource::class);
    }

    public function getPermission()
    {
        $permissions = $this->roleService->getPermission();
        return $this->paginatedResponse($permissions, PermissionsResource::class);
    }

    public function store(Request $request)
    {
        $role = $this->roleService->store($request->all());
        return $this->okResponse(new RoleResource($role), 'Created Role');
    }

    public function show($id)
    {
        $role = $this->roleService->show($id);
        return $this->okResponse(new RoleResource($role), 'Created Role');
    }

    public function update($id, Request $request)
    {
        $role = $this->roleService->update($id, $request->all());
        return $this->okResponse(new RoleResource($role), 'Created Role');
    }

    public function destroy($id)
    {
        $this->roleService->destroy($id);
        return $this->okResponse([], 'Deleted Role');
    }
}
