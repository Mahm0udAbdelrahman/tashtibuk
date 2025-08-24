<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailsCartResource extends JsonResource
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
            'total_price' => $this->sum('total'),
            "count_product"=> $this->count(),
            "delivery_service"=> 15,
            "total"=>$this->sum('total') + 15,
        ];
    }
}
