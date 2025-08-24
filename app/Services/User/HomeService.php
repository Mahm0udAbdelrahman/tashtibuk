<?php
namespace App\Services\User;

use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;


class HomeService
{

    public function products()
    {
        $products = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->paginate();

        return $products;
    }
}
