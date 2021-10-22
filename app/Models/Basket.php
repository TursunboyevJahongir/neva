<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Basket
 * @package App\Models
 * @property int user_id
 * @property int product_variation_id
 * @property int quantity
 * @property float sum
 */
class Basket extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'product_variation_id',
        'quantity'
    ];
    protected $appends = [
        'sum',
    ];
    public function product()
    {
        return $this->hasOne(ProductVariation::class, 'id', 'product_variation_id');
    }

    public function getSumAttribute()
    {
        return $this->product->price*$this->quantity;
    }
    public function setSumAttribute($value)
    {
        $this->sum = $value;
    }

}
