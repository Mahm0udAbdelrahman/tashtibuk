<?php

namespace App\Http\Resources\Delivery;

use App\Models\Setting;
use App\Models\OrderDelivery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletDeliveryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return 
        [
            'id' => $this->id,
            'price_without_cost_delivery' => $this->price_before_percentage ?? "0",
            'cost_delivery' => $this->cost_delivery ?? "0",
            'total' => (string) ($this->price_before_percentage + $this->cost_delivery),
            'order_balance' =>  OrderDelivery::where('order_id',$this->id)->value('order_value') ,

            'payment_method' => $this->payment_method ?? "0",
        ];
    }
}
