<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = ['product_title','price','quantity','order_id'];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function getPriceAttribute($value){
        return number_format($value,2);
    }

    public function getItemQuantityPriceAttribute(){
        return $this->price * $this->quantity;
    }
}
