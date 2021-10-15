<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasTranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Interest
 * @package App\Models
 * @property int id
 * @property array name
 * @property array description
 * @property string categories
 */
class Interest extends Model
{
    use HasFactory,HasTranslatableJson;

    protected $fillable = [
        'name',
        'description',
        'categories',
    ];

    protected $casts = [
        'name' => TranslatableJson::class,
        'description' => TranslatableJson::class,
        'categories' => 'array',
    ];
}
