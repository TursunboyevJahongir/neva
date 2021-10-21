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


    public function logicCoupon($products, Coupon $coupon)
    {
        $ids = [];
        switch ($coupon->coupon_type) {
            case CouponTypeEnum::PRODUCT:
                $ids[] = $coupon->object_id;
                break;
            case CouponTypeEnum::USER:
                if (Auth::id() === $coupon->object_id)
                    foreach ($products as $item)
                        $ids[] = $item->id;
                break;
            case CouponTypeEnum::CATEGORY:
                foreach ($products as $item)
                    if ($item->product->category_id === $coupon->object_id)
                        $ids[] =$item->id;
                break;
            case CouponTypeEnum::SHOP:
                foreach ($products as $item)
                    if ($item->product->shop_id === $coupon->object_id)
                        $ids[] =$item->id;
                break;
            case CouponTypeEnum::DELIVERY:
                break;
        }

        foreach ($products as $product)
            if (in_array($product->id, $ids) && empty($product->old_price) && empty($product->percent))
                $product->price =
                    $this->saleType($coupon->sale_type) ?
                        $product->price * (1 - $coupon->value / 100) :
                        $product->price - $coupon->value;

        return $products;
    }
}
