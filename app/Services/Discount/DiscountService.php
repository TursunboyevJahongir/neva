<?php

namespace App\Services\Discount;

use App\Models\Discount;

class DiscountService
{
    public function all()
    {
        return Discount::latest('id')
            ->get();
    }

    public function create(array $attributes)
    {

        $discount = Discount::create($attributes);


        return $discount;
    }

    public function update(array $attributes, Discount $discount)
    {
        $discount = Discount::update($attributes);
        return $discount;
    }

    public function delete(Discount $discount)
    {
        return $discount->delete();
    }
}
