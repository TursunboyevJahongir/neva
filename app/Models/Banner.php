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
        'description',
        'link'
    ];

    protected $casts = [
        'name' => TranslatableJson::class,
        'description' => TranslatableJson::class
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
