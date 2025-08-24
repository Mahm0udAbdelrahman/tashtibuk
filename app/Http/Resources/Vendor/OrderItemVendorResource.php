<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemVendorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
       
        return [
            'id'           => $this->id,
            'order_id'     => $this->order_id,
            'product_name' => $this->product->{'name_' . app()->getLocale()} ?? '',
            'product_image'=> $this->product->productImages->first()->image ?? '',
            'category_name'=> $this->product->category->{'name_' . app()->getLocale()} ?? '',
            'product_price'=> $this->product->price ?? '',
            'quantity'     => $this->quantity,
            'size'         => $this->size->{'name_'. app()->getLocale()} ?? '',
            'color'        => $this->color,
            'price'        => $this->price,
        ];
    }
}
