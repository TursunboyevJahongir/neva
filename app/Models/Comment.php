<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'text',
        'rating'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable')->where('cover_image', true)->withDefault(['url' => '/img/no-icon.png']);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')->where('cover_image', false);
    }


}
