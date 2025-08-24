<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'cost_delivery',
        'price_before_percentage',
        'price_after_percentage',
        'type',
        'order_balance',
        'total',
        'status',
        'name',
        'address',
        'payment_method',
        'payment_id',
        'payment_type',
        'phone',
        'payment_status',
        'number_product',
        'lat',
        'lng',
        'building_number',
        'floor_number',
        'apartment_number',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
     public function getStatusAttribute($value)
    {
        return __('status.' . $value);
    }
    
      public function OrderDeliveries()
    {
        return $this->hasMany(OrderDelivery::class);
    }

    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class);
    }
    
    public function deliveries()
    {
        return $this->belongsToMany(Delivery::class, 'order_deliveries', 'order_id', 'delivery_id')
            ->withPivot('status')
            ->withTimestamps();
    }
}
