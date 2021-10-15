<?php

namespace App\Services\Coupon;

use App\Models\Coupon;

class CouponService
{
    public function all()
    {
        return Coupon::latest('id')
            ->get();
    }

    public function create(array $attributes)
    {

        $discount = Coupon::create($attributes);

        return $discount;
    }

    public function update(array $attributes, Coupon $discount)
    {
        $discount = Coupon::update($attributes);
        return $discount;
    }

    public function delete(Coupon $discount)
    {
        return $discount->delete();
    }
}
