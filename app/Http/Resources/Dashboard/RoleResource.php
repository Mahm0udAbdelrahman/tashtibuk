<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "name"=> $this->name,
            "permissions_count" => count($this->permissions) ??0,
            "permissions" => $this->permissions->pluck('name'),
            "users_count"=> count($this->users) ?? 0,
        ];
    }
}
