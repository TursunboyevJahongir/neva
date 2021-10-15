<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserCoupons
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int coupon_id
 * @property Carbon start_at
 * @property Carbon end_at
 * @property User user
 * @property Coupon coupon
 */
class UserCoupons extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coupon_id',
        'end_at',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
}
