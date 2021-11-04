<?php

namespace App\Services\Discount;

use App\Models\Discount;
use App\Models\Image;

class DiscountService
{
    /**
     * @var Image
     */
    private $image;

    public function __construct()
    {
        $this->image = new Image();
    }

    public function all()
    {
        return Discount::latest('id')
            ->get();
    }

    public function create(array $attributes)
    {

        $discount = new Discount([
            'product_id' => $attributes['product'],
            (($attributes['discount'] == 'price') ? 'discount_price' : 'discount_percent') => $attributes['input'],
            'start_at' => $attributes['start'],
            'end_at' => $attributes['end'],
        ]);

        $discount->setTranslations('name', $attributes['name']);
        $discount->save();

        return $discount;
    }

    public function update(array $attributes, Discount $discount)
    {
        $discount->fill([
            'product_id' => $attributes['product'],
            (($attributes['discount'] == 'price') ? 'discount_price' : 'discount_percent') => $attributes['input'],
            'start_at' => $attributes['start'],
            'end_at' => $attributes['end'],
        ]);

        if ($attributes['discount'] == 'price')
            $discount->discount_percent = null;
        else
            $discount->discount_price = null;

        $discount->setTranslations('name', $attributes['name']);
        $discount->save();
        return $discount;
    }

    public function delete(Discount $discount)
    {
        return $discount->delete();
    }
}
