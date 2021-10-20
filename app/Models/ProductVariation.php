<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasTranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;


/**
 * Class Product
 * @package App\Models
 * @property int id
 * @property int product_id
 * @property TranslatableJson name
 * @property string image
 * @property string image_url
 *  * @OA\Schema(
 *     title="Product model",
 * )
 */
class ProductVariation extends Model
{
    use HasFactory, SoftDeletes, HasTranslatableJson;

    protected $fillable = [
        'product_id',
        'image',
        'name',
    ];

    protected $casts = [
        'name' => TranslatableJson::class
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function properties(): HasMany
    {
        return $this->hasMany(VariationProperty::class, 'variation_id', 'id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? URL::to($this->image) : null;
    }

    public function getFullNameAttribute()
    {
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
