<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total',
        'payment_method',
        'verification',
        'delivery_method',
        'address_id',
        'details',
        'cash_payment_amount',
    ];

    // Relación: Un pedido pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Un pedido pertenece a una dirección
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // Relación: Un pedido tiene muchos items (relación muchos a muchos)
    public function items()
    {
        return $this->belongsToMany(Item::class, 'order_item')
            ->withPivot('quantity', 'price_unit')
            ->withTimestamps();
    }
}
