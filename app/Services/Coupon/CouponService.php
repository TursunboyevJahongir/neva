<?php

namespace App\Services\Coupon;

use App\Enums\CouponTypeEnum;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CouponService
{
    public function all()
    {

        return Auth::user()->user_coupons()->get();
    }

    public function calcBasket($coupon)
    {

    }

    public function logicCoupon( $products,Coupon $coupon)
    {
switch ($coupon->coupon_type){
    case CouponTypeEnum::PRODUCT:
        
        break;
    case CouponTypeEnum::USER:

        break;
    case CouponTypeEnum::CATEGORY:

        break;
    case CouponTypeEnum::SHOP:

        break;
    case CouponTypeEnum::DELIVERY:

        break;
}
    }
}
