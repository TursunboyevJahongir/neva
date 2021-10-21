<?php

namespace App\Services\Coupon;

use App\Enums\CouponTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Collection;

class CouponService
{
    public function all()
    {

        return Auth::user()->user_coupons()->get();
    }

    public function calcBasket($coupon)
    {

    }

    public function saleType($type)
    {
        switch ($type) {
            case SaleTypeEnum::PRICE:
                return false;
            case SaleTypeEnum::PERCENT:
                return true;
        }
        return true;
    }

    public function logicCoupon(Collection $products, Coupon $coupon)
    {

        switch ($coupon->coupon_type) {
            case CouponTypeEnum::PRODUCT:
                $product = $products->find($coupon->object_id);
                if (!empty($product))
                    $product->price =
                        $this->saleType($coupon->sale_type) ?
                            $product->price * (1 - $coupon->value / 100) :
                            $product->price - $coupon->value;
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
