<?php

namespace App\Http\Resources\Delivery;

use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
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
            'type_motorcycles' => $this->type_motorcycles,
            'id_card' => $this->id_card,
            'driving_license' => $this->driving_license,
            'vehicle_license' => $this->vehicle_license,
            'lat'=> $this->lat,
            'lng'=> $this->lng,
            'address'=> $this->address,
            'image' => $this->image,
            'is_active' => $this->is_active
        ];
    }
}
