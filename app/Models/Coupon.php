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
        'description' => TranslatableJson::class
    ];

    /**
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
