<?php

namespace App\Http\Resources\User;

use App\Models\RefundRequest;
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
            'id'  => $this->id,
            'price' => $this->total,
            'count_product' => $this->items->count(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'status' => RefundRequest::where('order_id', $this->id)->value('status') ?? '0',

        ];
    }
}
