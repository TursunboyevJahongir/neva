<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasHeaders;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory,
        HasHeaders;

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
        'work_day'=>'array',
        'name' => TranslatableJson::class,
        'description' => TranslatableJson::class
    ];
    
}
