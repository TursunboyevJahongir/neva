<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasTranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory,
        HasTranslatableJson;

    protected $fillable = [
        'name'
    ];

    protected $casts = [
        'name' => TranslatableJson::class
    ];

    public function values()
    {
        return $this->hasMany(ProductAttributeValue::class, 'product_attribute_id', 'id');
    }
}
