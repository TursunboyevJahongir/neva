<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Enums\UserStatusEnum;
use App\Http\Resources\Api\v1\InterestResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class Client
 * @package App\Models
 * @property int id
 * @property int main_address_id
 * @property string first_name
 * @property string last_name
 * @property string phone
 * @property string email
 * @property GenderEnum gender
 * @property array interests
 * @property UserStatusEnum status
 * @property Carbon birthday
 * @property int district_id
 * @property string address
 * @property District district
 * @property Image avatar
 *  * @OA\Schema(
 *     title="User model",
 *     description="User model",
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public const CUSTOMER = 'customer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'birthday',
        'login',
        'email',
        'phone',
        'phone_verified_at',
        'email_verified_at',
//        'district_id',
        'main_address_id',
        'gender',
        'status',
        'password',
        'interests',
        'main_card',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'login',
    ];

    protected $casts = [
        'birthday' => 'datetime:Y-m-d',
        'phone_verified_at' => 'datetime:Y-m-d H:i',
        'email_verified_at' => 'datetime:Y-m-d H:i',
        'interests' => 'array',
    ];

    public function getAgeAttribute(): ?int
    {
        return $this->birthday ? date_diff($this->birthday, Carbon::now())->y : null;
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = bcrypt($value);
    }

    public function avatar(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class, 'user_id');
    }

    public function kids(): HasMany
    {
        return $this->hasMany(Kids::class, 'parent_id');
    }

    /**
     * @return BelongsTo
     */
    public function main_address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'main_address_id');
    }

    public function social_accounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class)->with('social_accounts');
    }
}
