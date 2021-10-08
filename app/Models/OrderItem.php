<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id', 'id')->withTrashed();
    }
}
