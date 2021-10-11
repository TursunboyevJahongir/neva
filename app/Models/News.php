<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasTranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory,HasTranslatableJson;
    protected $fillable = [

        'name',
        'description',
        'active',
        'position',
    ];
    protected $casts = [
        'active' => 'boolean',
        'name' => TranslatableJson::class,
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
    public function getSubDescriptionAttribute(): string
    {
        return !is_object($this->description) ? Str::limit($this->description, 15, '...') : "";
    }
}
