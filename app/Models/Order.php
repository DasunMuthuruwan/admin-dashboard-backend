<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = ['first_name', 'last_name', 'email'];

    // protected $casts = ['total' => 'float'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // get total price of orderItems using accessor

    public function getTotalAttribute()
    {
        return $this->orderItems->sum(function (OrderItem $order) {
            return $order->quantity * $order->price;
        });
    }
    // get full name using accessor
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
