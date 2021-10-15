<?php

namespace App\Models;

use App\Enums\SocialiteEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Kids
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property string user_socialite_id
 * @property SocialiteEnum socialite
 * @property User user
 */
class SocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'socialite',
        'user_id',
        'user_socialite_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
