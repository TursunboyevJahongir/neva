<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'number',
        'hide_number',
        'expire',
        'token',
        'verify',
    ];

    protected $casts = [
        'verify' => 'boolean',
    ];
}
