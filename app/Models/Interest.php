<?php

namespace App\Models;

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
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'categories',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'categories' => 'array',
    ];

    public function getNameUzAttribute()
    {
        return $this->exists ? $this->name['uz'] : '';
    }

    public function getNameRuAttribute()
    {
        return $this->exists ? $this->name['ru'] : '';
    }

    public function getNameEnAttribute()
    {
        return $this->exists ? $this->name['en'] : '';
    }

    public function getDescriptionUzAttribute(): string
    {
        return Str::limit($this->description['uz'], 10, '...');
    }

    public function getDescriptionRuAttribute(): string
    {
        return Str::limit($this->description['ru'], 10, '...');
    }

    public function getDescriptionEnAttribute(): string
    {
        return Str::limit($this->description['en'], 10, '...');
    }
}
