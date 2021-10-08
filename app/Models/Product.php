<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasTranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Class Product
 * @package App\Models
 * @property int id
 * @property int category_id
 * @property int shop_id
 * @property int position
 * @property array product_attribute_ids
 * @property TranslatableJson name
 * @property TranslatableJson description
 * @property string sku
 * @property string slug
 * @property int rating
 * @property double min_price
 * @property double max_price
 * @property double min_old_price
 * @property int max_percent
 * @property int delivery_price
 * @property boolean active
 * @property Image image
 * @property Image images
 *  * @OA\Schema(
 *     title="Product model",
 * )
 */
class Product extends Model
{
    use HasFactory,
        HasTranslatableJson, SoftDeletes;

    protected $fillable = [
        'name',
        'shop_id',
        'category_id',
        'sku',
        'slug',
//        'product_attributes',
        'description',
        'rating',
        'min_old_price',
        'min_price',
        'max_price',
        'max_percent',
        'active',
        'delivery_price',
        'position',
        'tag',
    ];

    protected $casts = [
        'active' => 'boolean',
        'name' => TranslatableJson::class,
        'description' => TranslatableJson::class,
//        'product_attributes' => 'array',
    ];

    protected $with = [
        'variations'
    ];

//    public function productAttributes()
//    {
//        return ProductAttribute::whereIn('id', $this->product_attribute_ids)->get();
//    }

    public function single()
    {
        return $this->hasOne(ProductVariation::class, 'product_id', 'id');
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'product_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'product_id', 'id');
    }

    public function shop(): BelongsTo
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

    public function scopeActive($q)
    {
        return $q->where('active', '=', true);
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

    public function favorite()
    {
        return $this->hasMany(Favorite::class);
    }

    public function getInFavoriteAttribute(): bool
    {
        return $this->favorite()->where('user_id', auth('sanctum')->id())->exists();
    }

    public function getSubDescriptionAttribute(): string
    {
        return !is_object($this->description) ? Str::limit($this->description, 15, '...') : "";
    }
}
