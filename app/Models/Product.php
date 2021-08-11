<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = ['title', 'description', 'image', 'price'];

    // protected $visible = ['id', 'title', 'description', 'image', 'price'];

    // protected $appends = ['price_with_curency'];

    // protected $casts = ['price' => 'float'];

    public $timestamps = false;

    public function getPriceAttribute($value){
        return number_format($value,2);
    }

}
