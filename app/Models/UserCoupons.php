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
 * @property Carbon end_at
 * @property boolean used
 * @property User user
 */
class UserCoupons extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coupon',
        'end_at',
        'used',
        'coupon_info',
    ];

    protected $casts = [
        'used' => 'boolean',
        'coupon_info' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function scopeActive($q)
    {
        return $q->where('used', '=', false)->where('end_at', '>=', now());
    }
}
