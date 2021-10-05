<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_FINISHED = 'finished';
    protected $fillable = [
        'shop_id',
        'user_id',
        'quantity',
        'sum',
        'status',
        'name',
        'phone',
        'address',
        'comment',
        'total_price',
        'price_delivery',
        'delivery',

    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }


    public function scopeShopOwner($query, $shop_id)
    {
        if ($shop_id)
            return $query->where('shop_id', $shop_id);
    }

    public function scopeFilterByDate($query, $date)
    {
    }
}
