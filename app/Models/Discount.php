<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'active',
        'name',
        'name',
        'description',
        'product_ids',
        'discount_type',
        'discount_amount',
        'value',
        'expire_date',

    ];
    use HasFactory;
    protected $casts = [
        'name' => TranslatableJson::class,
        'expire_date'=>'date'
    ];

}
