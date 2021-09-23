<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasHeaders;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory,
        HasHeaders;

    protected $fillable = [
        'name',
        'shop_id',
        'category_id',
        'sku',
        'product_attribute_ids',
        'content',
        'rating',
        'min_price',
        'max_price',
        'active',
        'delivery_price',
    ];

    protected $casts = [
        'active' => 'boolean',
        'name' => TranslatableJson::class,
        'content' => TranslatableJson::class,
        'product_attribute_ids' => 'array',
    ];

    protected $with = [
        'variations'
    ];

    public function productAttributes()
    {
        return ProductAttribute::whereIn('id', $this->product_attribute_ids)->get();
    }

    public function single()
    {
        return $this->hasOne(ProductVariation::class, 'product_id', 'id');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'product_id', 'id');
    }


    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable')->where('cover_image', true)->withDefault(['url' => '/img/no-icon.png']);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')->where('cover_image', false);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product');
    }

    public function discount()
    {
    }

    public function shopOwner($query, $shop_id)
    {
        if ($shop_id)
            return $query->where('shop_id', $shop_id);
    }

    public function getSalePriceAttribute()
    {
        return "$this->min_price - $this->max_price";
    }
}
