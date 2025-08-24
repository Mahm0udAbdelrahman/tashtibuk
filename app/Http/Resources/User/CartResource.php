<?php
namespace App\Http\Resources\User;

use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {

        

        $colorSize = $this->product->colorSizes
            ->where('color', $this->color)
            ->where('size_id', $this->size->id)
            ->first();

        return [
            "id"               => $this->id,
            'category_product' => $this->product->category->{'name_' . app()->getLocale()},
            'color'            => $this->color,
            'size'             => $this->size->{'name_' . app()->getLocale()},

            "product_id"       => $this->product->id,
            "product_name"     => $this->product->{'name_' . app()->getLocale()},

            "product_quantity" => $colorSize ? $colorSize->quantity : 0,
            "product_image"    => $this->product->productImages->pluck('image')->first(),
            "product_price"    => $this->product->price,
            "quantity"         => $this->quantity,
            "total"            => $this->total,
        ];
    }
}
