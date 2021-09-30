<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ProductVariation extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'product_attribute_value_ids',
        'quantity',
        'old_price',
        'price',
        'percent',
    ];

    protected $casts = [
        'product_attribute_value_ids' => 'array'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function productAttributeValues()
    {
        return ProductAttributeValue::whereIn('id', $this->product_attribute_value_ids)->get();
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->withDefault(['url'=>'/img/no-icon.png']);
    }

    public function getFullNameAttribute() {
        $res = null;
        $global_values = ProductAttributeValue::all(); // optimize maybe
        if (count($this->product_attribute_value_ids) > 0) {
            $res = '';
            $vals = array_values($this->product_attribute_value_ids);
            $last = end($vals);
            foreach ($this->product_attribute_value_ids as $variation_value_id) {
                foreach ($global_values as $v) {
                    if ($v->id == $variation_value_id) {
                        $res .= $v->name;
                    }
                }
                if ($last != $variation_value_id) {
                    $res .= ', ';
                }
            }
        }
        return $res;
    }
}
