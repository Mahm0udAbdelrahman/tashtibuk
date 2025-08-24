<?php
namespace App\Services\Dashboard;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleService
{
    public function __construct(public Role $model)
    {}
    public function index()
    {
        return $this->model->paginate(10);
    }
    public function getPermission()
    {
        return Permission::paginate(10);
    }

    public function store($data)
    {
        $role = $this->model->create(['name' => $data['name']]);
        $role->syncPermissions($data['permission_id']);
        return $role;
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, $data)
    {
        $role = $this->model->findOrFail($id);

        if (isset($data['name'])) {
            $role->name = $data['name'];
            $role->save();
        }

        if (isset($data['permission_id'])) {
            $role->syncPermissions($data['permission_id']);
        }

        return $role;
    }

    public function destroy($id)
    {
        $role = $this->model->findOrFail($id);
        $role->revokePermissionTo($role->permissions);
        $role->delete();
    }
}
