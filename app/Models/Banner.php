<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasTranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory, HasTranslatableJson;

    protected $fillable = [
        'name',
    //    'description',
        'link',
        'object',
        'object_id',
        'active',
        'position',
    ];

    protected $casts = [
       // 'description' => TranslatableJson::class
    ];
    public function scopeActive($q)
    {
        return $q->where('active', '=', true)->orderBy('position','DESC');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
