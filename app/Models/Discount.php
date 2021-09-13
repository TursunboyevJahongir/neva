<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'product_id',
        'discount_price',
        'discount_percent',
        'start_at',
        'end_at',

    ];
    protected $casts = [
        'name' => TranslatableJson::class,
    ];
    protected $dates = ['starts_at', 'ends_at'];

}
