<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
     protected $fillable =
    [
        'vendor_id',
        'delivery_id',
        'withdrawal',
        'status'
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
