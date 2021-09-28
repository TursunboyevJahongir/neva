<?php

namespace App\Services\Basket;

use App\Models\Basket;
use Illuminate\Http\Request;
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

        foreach ($basket->get() as $item) {
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

    //WishList
    public function addToWish($product)
    {
        $wish_list = [];
        $getWish = session()->get('wishList');
        $id = $product->id;
        if (is_array($getWish)) {
            if (!in_array($id, $getWish)) {
                $wish_list = $getWish;
                array_push($wish_list, $id);
                session()->put('wishList', $wish_list);
            } else {
                $wish_list = $getWish;
                $key = array_search($id, $wish_list, true);
                unset($wish_list[$key]);
                session()->put('wishList', $wish_list);
            }
        } else {
            array_push($wish_list, $id);
            session()->put('wishList', $wish_list);
        }
        if (empty(session()->get('wishList'))) {
            return 0;
        } else {
            return count(session()->get('wishList'));
        }
    }

}