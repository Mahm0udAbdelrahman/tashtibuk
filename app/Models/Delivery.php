<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Delivery extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'type_motorcycles',
        'id_card',
        'driving_license',
        'vehicle_license',
        'image',
        'lat',
        'lng',
        'address',
        'code',
        'expire_at',
        'fcm_token',
        'email_verified_at',
        'is_active',
        'status',
        'balance'
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
    
    public function OrderDeliveries()
    {
        return $this->hasMany(OrderDelivery::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_deliveries', 'delivery_id', 'order_id')
            ->withPivot('status')
            ->withTimestamps();
    }

}
