<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class Client
 * @package App\Models
 * @property int $id
 * @property string $full_name
 * @property string $phone
 * @property string $email
 * @property Carbon $birthday
 * @property int $district_id
 * @property string $address
 * @property District $district
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'birthday',
        'login',
        'email',
        'phone',
        'district_id',
        'address',
        'gender',
        'status',
        'password',
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
    ];

    public function getAgeAttribute(): ?int
    {
        return $this->birthday ? date_diff($this->birthday, Carbon::now())->y : null;
    }

    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = bcrypt($value);
    }
    public function avatar(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'id', 'user_id');
    }
}
