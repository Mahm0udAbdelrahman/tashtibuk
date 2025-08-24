<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BestSellingProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->product->id,
            "name"=> $this->product->{'name_' . app()->getLocale()},
            "name_owner"=> $this->product->vendor->shop_name,
            "price"=> $this->product->price,
            "images"=> $this->product->productImages->pluck('image')->first(),
            'category' => $this->product->category->{'name_' . app()->getLocale()},
        ];
    }
}
