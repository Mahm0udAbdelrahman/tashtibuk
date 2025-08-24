<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user_name' => $this->user->name,
            'vendor_name' => optional($this->items->first())->product->vendor->name ?? '',
            'cost_delivery' => $this->cost_delivery,
            'price_before_percentage' => $this->price_before_percentage,
            'price_after_percentage' => $this->price_after_percentage,
            'type' => $this->type,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,

        ];
    }
}
