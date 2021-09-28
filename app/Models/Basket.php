<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_variation_id',
        'quantity'
    ];
    public function product()
    {
        return $this->hasOne(ProductVariation::class, 'id', 'product_variation_id');
    }
}
