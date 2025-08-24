<?php
namespace App\Services\Delivery;

use App\Models\Order;

class WalletService
{
    public function __construct(public Order $order)
    {}

    public function index()
    {
        $details = $this->order
            ->whereHas('OrderDeliveries', function ($query) {
                $query->where('delivery_id', auth('delivery')->id())
                      ->where('status', '1');
            })
            ->with(['items.product'])
            ->get();
            
        return $details;
    }
}
