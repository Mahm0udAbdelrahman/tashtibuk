<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefundRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->items->map(function ($q) {
            return [
                'id' =>   $q->id,
                'category_product' => $q->product->category?->{'name_' . app()->getLocale()},
                'color'            => $q->color,
                'size'             => $q->size?->{'name_' . app()->getLocale()},
                'product_id'       => $q->product?->id,
                'product_name'     => $q->product?->{'name_' . app()->getLocale()},
                'product_image'    => $q->product?->productImages?->pluck('image')->first(),
                'product_price'    => $q->product?->price,
                'quantity'         => (int) $q->quantity,
                'total'            => $q->total,
            ];
        })->toArray();
    }
}
