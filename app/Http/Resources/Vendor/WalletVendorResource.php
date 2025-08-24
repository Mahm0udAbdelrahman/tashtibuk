<?php

namespace App\Http\Resources\Vendor;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletVendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'percentage' => Setting::value('percentage'),
            'price_before_percentage' => $this->price_before_percentage ?? "0",
            'price_after_percentage' => $this->price_after_percentage ?? "0",
            'cost_delivery' => $this->cost_delivery ?? "0",
            'total' => $this->total ?? "0",
            'payment_method' => $this->payment_method ?? "0",
            'order_balance'           => $this->order_balance,
            'type' => $this->type,
        ];
    }
}
