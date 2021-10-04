<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'shop_id',
        'order_id',
        'product_variation_id',
        'sku',
        'quantity',
        'price',
        'sum'
    ];
}
