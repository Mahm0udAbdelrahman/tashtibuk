<?php
namespace App\Http\Resources\Vendor;

use App\Models\OrderDelivery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderVendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        $firstItem = $this->items()->first();
        return [
            'id'            => $this->id,
            'vendor_id'     => $firstItem->product->vendor->id ?? null,
            'count_product' => $this->items->count(),
            'cost_delivery' => $this->cost_delivery,
            'number_order'  => $this->id,
            'address'       => $this->address,
            'lat'           => $this->lat,
            'lng'           => $this->lng,
             'total' => (string) ($this->price_before_percentage + $this->cost_delivery),
            'status'        => $this->status,
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
            'is_delivery'   => auth('delivery')->check()
            ? (OrderDelivery::where('order_id', $this->id)
                    ->where('delivery_id', auth('delivery')->id())
                    ->value('status') ?? "-1")
            : "-1",
            'vendor'        => [
                'name'    => $firstItem->product->vendor->shop_name ?? null,
                'phone'   => $firstItem->product->vendor->shop_phone ?? null,
                'address' => $firstItem->product->vendor->address ?? null,
                'lat'     => $firstItem->product->vendor->lat ?? null,
                'lng'     => $firstItem->product->vendor->lng ?? null,
            ],

        ];
    }

}
