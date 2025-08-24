<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColorSize extends Model
{
    protected $table = 'product_color_sizes';

public function product()
{
    return $this->belongsTo(Product::class);
}



public function size()
{
    return $this->belongsTo(Size::class);
}
    protected $fillable = [
        'product_id',
        'color',
        'size_id',
        'quantity'
    ];

   protected $casts = [
        'quantity' => 'integer',
        'size_id' => 'integer',
        'product_id' => 'integer',
    ];
}
