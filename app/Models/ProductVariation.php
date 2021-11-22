<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProductVariation
 * @package App\Models
 * @property int id
 * @property int product_id
 * @property int property_id
 * @property array combs_attributes
 * @property int quantity
 * @property float old_price
 * @property float price
 * @property int percent
 */
class ProductVariation extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'product_id',
        'property_id',
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

}
