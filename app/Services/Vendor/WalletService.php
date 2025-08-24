<?php
namespace App\Services\Vendor;

use App\Models\Order;

class WalletService
{
    public function __construct(public Order $order)
    {}

    public function index()
    {
         $details = $this->order->whereHas('items', function ($query) {
            $query->whereHas('product', function ($q) {
                $q->where('vendor_id', auth('vendor')->id());
            });
        })
            ->with(['items.product'])
            ->get();
            
            return $details;
    }

}
