<?php

namespace App\Services\Coupon;

use App\Enums\CouponTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Shop;
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


    public function logicCoupon(&$basket, Coupon $coupon)
    {
        $ids = [];
        switch ($coupon->coupon_type) {
            case CouponTypeEnum::PRODUCT:
                $ids[] = $coupon->object_id;
                break;
            case CouponTypeEnum::USER:
                if (Auth::id() === $coupon->object_id)
                    $ids = $basket->pluck('product_variation_id')->toArray();
                break;
            case CouponTypeEnum::CATEGORY:
                foreach ($basket->get() as $item)
                    if ($item->product->product->category_id === $coupon->object_id)
                        $ids[] = $item->id;
                break;
            case CouponTypeEnum::SHOP:
                foreach ($basket->get() as $item)
                    if ($item->product->product->shop_id === $coupon->object_id)
                        $ids[] = $item->id;
                break;
            case CouponTypeEnum::DELIVERY:
                break;
            default:
                $ids = $basket->pluck('product_variation_id')->toArray();
                break;

        }

        foreach ($basket->get() as $item) {
            $product = $item->product;
            if (in_array($product->id, $ids) && empty($product->old_price) && ($coupon->value > 0) &&
                empty($product->percent) && (empty($item->limit_product) || $coupon->limit_product >= $item->quantity)) {
                if ($this->saleType($coupon->sale_type)) {
                    $item->sum = $item->sum * (1 - $coupon->value / 100);
                } else {
                    if (($coupon->value - $item->sum) > 0) {
                        $coupon->value -= $item->sum;
                        $item->sum = 0;
                    } else {
                        $item->sum -= $coupon->value;
                        $coupon->value = 0;
                    }
                }
                if (!empty($coupon->limit_product))
                    $coupon->limit_product -= $item->quantity;
            }

        }

    }
}
