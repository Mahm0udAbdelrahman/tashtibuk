<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Integer;

class Favorite extends Model
{
    protected $fillable = [
        'favorite',
        'product_id',
        'user_id',
    ];

    protected $casts = [
    'product_id' => 'integer',
    'user_id' => 'integer',
];
    public function user()
    {
       return $this->belongsTo(User::class);
    }

    public function product()
    {
       return $this->belongsTo(Product::class);
    }
}
