<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasHeaders;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory,
        HasHeaders;

    protected $fillable = [
        'product_attribute_id',
        'name'
    ];

    protected $casts = [
        'name' => TranslatableJson::class
    ];

    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id', 'id');
    }
}
