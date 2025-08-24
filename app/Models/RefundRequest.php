<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'status',
        'reason',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'order_id' => 'integer',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function details()
    {
        return $this->hasMany(RefundRequestDetail::class, 'refund_request_id');
    }

    public function items()
    {
        return $this->belongsToMany(OrderItem::class, 'refund_request_details', 'refund_request_id', 'item_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'refund_request_details', 'refund_request_id', 'product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
