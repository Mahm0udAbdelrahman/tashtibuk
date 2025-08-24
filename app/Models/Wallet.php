<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable =
    [
        'vendor_id',
        'delivery_id',
        'vendor_wallet',
        'delivery_wallet',
        'cost_delivery',
        'total',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id','id');
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class,'delivery_id','id');
    }


}
