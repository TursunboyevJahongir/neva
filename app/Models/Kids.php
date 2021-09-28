<?php

namespace App\Models;


use App\Enums\GenderEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Kids
 * @package App\Models
 * @property int id
 * @property int parent_id
 * @property string full_name
 * @property array interests
 * @property GenderEnum gender
 * @property Carbon birthday
 * @property Image avatar
 * @property User parent
 */
class Kids extends Model
{

    public const CUSTOMER = 'kid';

    protected $fillable = [
        'full_name',
        'birthday',
        'interests',
        'gender',
        'parent_id',
    ];


    protected $casts = [
        'birthday' => 'datetime:Y-m-d',
        'interests' => 'array',
    ];

    public function getAgeAttribute(): ?int
    {
        return $this->birthday ? date_diff($this->birthday, Carbon::now())->y : null;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
