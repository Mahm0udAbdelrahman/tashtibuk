<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Vendor extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'shop_name',
        'description',
        'balance',
        'shop_phone',
        'lat',
        'lng',
        'address',
        'logo',
        'background',
        'to',
        'form',
        'code',
        'expire_at',
        'fcm_token',
        'email_verified_at',
        'status',
        'is_delivery',
        'id_card'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'expire_at'         => 'datetime',
        'password'          => 'hashed',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
     public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, Product::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderItem::class, 'product_id', 'id', 'id', 'order_id')
            ->distinct();
    }

}
