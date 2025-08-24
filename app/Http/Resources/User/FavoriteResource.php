<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
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
            "product_id"=> $this->product_id,
            "image"=> $this->product->productImages->pluck('image')->first(),
            "name"=> $this->product->{'name_' . app()->getLocale()},
            "price"=> $this->product->price,
            'category'=> $this->product->category->{'name_' . app()->getLocale()},
        ];
    }
}
