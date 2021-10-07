<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasTranslatableJson;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory, HasTranslatableJson, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'slug',
        'active',
        'delivery_price',
        'delivery_time',
        'work_day',
        'open',
        'close',
        'pickup',
        'refund',
    ];

    protected $casts = [
        'active' => 'boolean',
        'work_day' => 'array',
        'description' => TranslatableJson::class
    ];

}
