<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'size_id',
        'color',
        'quantity',
        'price',
        'total',
    ];

    /**
     * Define the relationship with the Order model.
     */

    protected $casts = [
        'order_id' => 'integer',
        'product_id' => 'integer',
        'size_id' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }



    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

}
