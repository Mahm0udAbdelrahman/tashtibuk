<?php
namespace App\Http\Resources\Vendor;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = auth('sanctum')->user();

        return [
            "id"           => $this->id,
            "id_owner"     => optional($this->vendor)->id,
            "name_owner"   => optional($this->vendor)->shop_name,
            "phone_owner"  => optional($this->vendor)->phone,
            "image_owner"  => optional($this->vendor)->logo,
            "category"     => optional($this->category)->{'name_' . app()->getLocale()},
            "price"        => $this->price,
            "name"         => $this->{'name_' .app()->getLocale()},
            'name_en'    => $this->name_en,
            'name_ar'    => $this->name_ar,
            "description"  => $this->{'description_' . app()->getLocale()},
            "description_ar"  => $this->description_ar,
            "description_en"  => $this->description_en,
            "sub_category" => optional($this->subCategory)->{'name_' . app()->getLocale()},
            "variants"     => $this->colorSizes->map(function ($item) {
                return [
                     'variant_id' => $item->id,
                    'size_id'  => $item->size_id,
                    'size_name'  => $item->size->{'name_' . app()->getLocale()},
                    'color' => $item->color,
                    'quantity' => $item->quantity,
                ];
            }),
            "images"       => $this->productImages->pluck('image')->first(),
             'all_images'  => $this->productImages->pluck('image'),
            'is_fav'       => $user && (int) Favorite::where('user_id', $user->id)->where('product_id', $this->id)->value('favorite') ? 1 : 0,
            "created_at"   => $this->created_at,
        ];
    }

}
