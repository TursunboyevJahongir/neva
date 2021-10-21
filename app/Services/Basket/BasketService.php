<?php

namespace App\Services\Basket;

use App\Models\Basket;
use App\Models\Coupon;
use App\Models\ProductVariation;
use App\Services\Coupon\CouponService;
use Illuminate\Support\Facades\Auth;

class BasketService
{
    public function all($orderBy = 'created_at', $sort = 'DESC', $size = 10)
    {
        $sum = 0;
        $basket = Basket::query()
            ->where('user_id', Auth::id())
//            ->when($search, function ($query, $search) {
//                return $query->where('message', 'ilike', "%$search%");
//            })
            ->orderBy($orderBy, $sort);
        if (!empty(request('coupon')))
            $basket = (new CouponService())->logicCoupon(ProductVariation::query()->find([1,2,3]), Coupon::query()->active()->where('code', '=', request('coupon'))->first());
        foreach ($basket->get()as $item) {
            $sum += $item->product->price * $item->quantity;
        }

        $basket = $basket->paginate($size);

        return ['basket' => $basket, 'append' => ['sum' => $sum]];
    }

    //Cart
    public function cart(array $attributes)
    {
        $product_id = $attributes['product_id'];
        $quantity = $attributes['quantity'] ?? 1;
        Basket::query()->updateOrCreate(
            ['user_id' => Auth::id(), 'product_variation_id' => $product_id]

        )->increment('quantity', $quantity);

        return 1;

    }

    public function reCalc($product)
    {
        $id = $product->id;
        $cart = session()->get('cart');
        if (!isset($cart['products'][$id])) return false;
        $qtyMinus = $cart['products'][$id]['qty'];
        $sumMinus = $cart['products'][$id]['qty'] * $cart['products'][$id]['price'];
        $cart['qty'] -= $qtyMinus;
        $cart['sum'] -= $sumMinus;
        unset($cart['products'][$id]);
        session()->put('cart', $cart);
    }


}
