<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundRequestDetail extends Model
{
    protected $fillable = [
        'refund_request_id',
        'quantity',
        'item_id',
        'product_id',
    ];

    public function refundRequest()
    {
        return $this->belongsTo(RefundRequest::class, 'refund_request_id');
    }

    public function item()
    {
        return $this->belongsTo(OrderItem::class, 'item_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
