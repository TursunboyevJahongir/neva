<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasTranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intro extends Model
{
    use HasFactory, HasTranslatableJson;

    protected $table = 'intro';

    protected $fillable = [
        'title',
        'description',
    ];

    protected $casts = [
        'title' => TranslatableJson::class,
        'description' => TranslatableJson::class,
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
