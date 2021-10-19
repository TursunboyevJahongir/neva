<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Enums\CouponTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Traits\HasTranslatableJson;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Class Coupon
 * @package App\Models
 * @property int id
 * @property int creator_id
 * @property int object_id
 * @property string code
 * @property string description
 * @property Carbon start_at
 * @property Carbon end_at
 * @property CouponTypeEnum coupon_type
 * @property SaleTypeEnum sale_type
 * @property int value
 * @property int count
 * @property int $price to min price
 * @property boolean active
 * @property User creator
 */
class Coupon extends Model
{
    use HasFactory, HasTranslatableJson;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'creator_id',
        'object_id',
        'coupon_type',
        'code',
        'name',
        'description',
        'start_at',
        'end_at',
        'sale_type',
        'value',
        'count',
        'price',
        'active',
    ];

    protected $casts = [
        'start_at' => 'datetime:Y-m-d H:i:s',
        'end_at' => 'datetime:Y-m-d H:i:s',
        'name' => TranslatableJson::class,
        'description' => TranslatableJson::class
    ];

    /**
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable')->where('cover_image', false)->withDefault(['url' => '/img/no-icon.png']);
    }
    public function scopeActive($q)
    {
        return $q->whereNull('active');
    }
    public function scopeAuthUser($q)
    {
        return $q->where('coupon_type', '=', 'user')->where('object_id', '=', Auth::id());
    }


}
