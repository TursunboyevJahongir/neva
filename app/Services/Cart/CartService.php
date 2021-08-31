<?php

namespace App\Services\Cart;

class CartService
{
    //Cart
    public function addToCart($product, $qty = 1)
    {
        $cart = session()->get('cart');
        $id = $product->id;
        if (isset($cart['products'][$id]))
            $cart['products'][$id]['qty'] += $qty;
        else {
            $cart['products'][$id] = [
                'qty' => $qty,
                'name' => $product->product->name,
                'price' => $product->price,
            ];
        }
        $cart['qty'] = isset($cart['qty']) ? $cart['qty'] + $qty : $qty;
        $cart['sum'] = isset($cart['sum']) ? $cart['sum'] + $qty * $product->price : $qty * $product->price;
        session()->put('cart', $cart);

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