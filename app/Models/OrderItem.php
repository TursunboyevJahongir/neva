<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'shop_id',
        'variation_property_id',
        'sku',
        'quantity',
        'sum',
        'price',
        'percent',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(VariationProperty::class, 'variation_property_id', 'id')->withTrashed();
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }
}
