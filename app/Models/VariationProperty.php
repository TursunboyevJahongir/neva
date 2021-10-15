<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariationProperty extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'product_id',
        'variation_id',
        'combs_attributes',
        'quantity',
        'old_price',
        'price',
        'percent',
    ];

    protected $casts = [
        'combs_attributes' => 'array'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'product_id', 'id');
    }
}
