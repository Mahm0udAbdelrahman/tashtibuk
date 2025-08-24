<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailsUserResource extends JsonResource
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
            'orders' => $this->orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'name' => $order->name,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'building_number' => $order->building_number,
                    'floor_number' => $order->floor_number,
                    'apartment_number' => $order->apartment_number,
                    'payment_method' => $order->payment_method,
                    'payment_status' => $order->payment_status,
                    'status' => $order->status,
                    'total' => $order->total,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'items' => $order->items->map(function ($item) {
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
            }),
        ];
    }
}
