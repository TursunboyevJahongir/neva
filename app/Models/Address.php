<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Address
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int $apartment квартира
 * @property int $storey этаж
 * @property int $intercom домофон
 * @property int $entrance подъезд
 * @property string $landmark ориентир
 * @property string address
 * @property string lat
 * @property string long
 *
 */
class Address extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'apartment',
        'storey',
        'intercom',
        'entrance',
        'landmark',

        'address',
        'lat',
        'long',
    ];
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function getLocationAttribute($value)
    {
        return $this->lat . ',' . $this->long;
    }
}
