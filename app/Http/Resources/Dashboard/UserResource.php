<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' =>  $this->name,
            'email'=> $this->email,
            'phone'=> $this->phone,
            'lat'=> $this->lat,
            'lng'=> $this->lng,
            'address'=> $this->address,
            'image' => $this->image,
            'birthday' => $this->birthday,
            'apartment_number' => $this->apartment_number,
            'building_number' => $this->building_number,
            'floor_number' => $this->floor_number,
            'role' => $this->getRoleNames() ?? null,
        ];
    }
}
