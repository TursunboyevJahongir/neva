<?php

namespace App\Services\Basket;

use App\Models\Basket;
use App\Models\Coupon;
use App\Models\ProductVariation;
use App\Models\UserCoupons;
use App\Services\Coupon\CouponService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class BasketService
{
    /**
     * @param string $orderBy
     * @param string $sort
     * @param int $size
     * @return array
     */
    public function all($orderBy = 'created_at', $sort = 'DESC', $size = 10)
    {
        $total = 0;
        $basket = Basket::query()
            ->where('user_id', Auth::id())
            ->orderBy($orderBy, $sort);

        foreach ($basket->get() as $item) {
            $total += $item->sum;
        }

        $basket = $basket->paginate($size);
        return ['basket' => $basket, 'append' => ['total' => $total]];
    }

    /**
     * @param $ids
     * @return array
     */
    public function selected($ids): array
    {
        $total = 0;
        $basket = Basket::query()
            ->where('user_id', Auth::id())
            ->whereIn('id', $ids);

        (new CouponService())->logicCoupon($basket);

        foreach ($basket as $item) {
            $total += $item->sum;
        }

        return ['basket' => $basket, 'append' => ['total' => $total]];
    }

    //Cart

    /**
     * @param array $attributes
     * @return int
     */
    public function cart(array $attributes)
    {
        $product_id = $attributes['product_id'];
        $quantity = $attributes['quantity'] ?? 1;
        Basket::query()->updateOrCreate(
            ['user_id' => Auth::id(), 'product_variation_id' => $product_id]

        )->increment('quantity', $quantity);

        return 1;

    }


}
