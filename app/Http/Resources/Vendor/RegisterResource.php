<?php

namespace App\Http\Resources\Vendor;

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
            'shop_name' => $this->shop_name,
            'description' => $this->description,
            'shop_phone' => $this->shop_phone,
            'lat'=> $this->lat,
            'lng'=> $this->lng,
            'address'=> $this->address,
            'to' => $this->to,
            'form' => $this->form,
            'background' => $this->background,
            'logo' => $this->logo,
            'rate' => Rate::where('vendor_id',$this->id)->average('rate') ?? '',
            'status' => $this->status



         ];
    }
}
