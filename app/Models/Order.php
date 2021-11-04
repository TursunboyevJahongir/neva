<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_coupon_id',
        'address_id',
        'quantity',
        'sum',
        'status',
        'full_name',
        'phone',
        'address',
        'comment',
        'total_price',
        'price_delivery',
        'delivery',

    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }




    public function scopeFilterByDate($query, $date)
    {
    }
}
