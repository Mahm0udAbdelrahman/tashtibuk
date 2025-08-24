<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
     protected $fillable = [
        'order_id',
        'delivery_id',
        'order_value',
        'status',

    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }


}
