<?php
namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        $permissions = collect();

        foreach ($this->getRoleNames() as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $permissions = $permissions->merge($role->permissions->pluck('name'));
            }
        }

        return [
            "id"                 => $this->id,
            'name'               => $this->name,
            'email'              => $this->email,
            'role'               => $this->getRoleNames(),
            'permissions'        => $permissions->unique()->values(),
        ];
    }

}
