<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'description' =>  $this->{'description_' . app()->getLocale()},
            "phone"=> $this->phone,
            'email'=> $this->email,
            'percentage'=> $this->percentage,
            'delivery_distance'=> $this->delivery_distance,
            'price_per_km'=> $this->price_per_km,
            'balance'=> $this->balance,
        ];
    }
}
