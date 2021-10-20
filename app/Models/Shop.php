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
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'slug',
        'delivery_price',
        'delivery_time',
        'work_day',
        'open',
        'close',
        'pickup',
        'refund',
        'is_brand',
        'position',
        'active',
    ];

    use HasFactory, HasTranslatableJson, SoftDeletes;

    protected $casts = [
        'active' => 'boolean',
        'work_day' => 'array',
        'description' => TranslatableJson::class
    ];
    public function scopeActive($q)
    {
        return $q->where('active', '=', true)->orderBy('position','DESC');
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable')->withDefault(['url' => '/img/no-icon.png']);
    }
}
