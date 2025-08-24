<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailsOrderResource extends JsonResource
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
            'name' => $this->name,
            'phone' =>$this->phone,
            'address' => $this->address,
            'building_number' => $this->building_number,
            'floor_number' => $this->floor_number,
            'apartment_number' => $this->apartment_number,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product->{'name_' . app()->getLocale()},
                    'product_image' => $item->product->productImages->first()->image ?? null,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'size' =>  $item->size->{'name_' . app()->getLocale()} ?? null,
                    'color' => $item->color,
                    'total' => $item->total,
                    ];
            }),







        ];
    }
}
