<?php

namespace App\Services\Orders;

use App\Models\Setting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class OrderService
{
    public function all($shop_id = false)
    {
        return Order::query()
            ->latest('id')
            ->shopOwner($shop_id)
            ->paginate(config('app.per_page'));
    }

    public function getOrder()
    {

        return session()->get('order');
    }

    public function setOrderCart()
    {
        $order = session()->get('order');
        $order['user_id'] = auth()->user()->id;
        $cart = session()->get('cart');
        $order['shop_id'] = ProductVariation::find(array_key_first($cart['products']))->product->shop_id;
        $order['quantity'] = 0;
        $order['sum'] = 0;
        foreach ($cart['products'] as $key => $value) {
            $product = ProductVariation::find($key);
            if (empty($product)) return false;
            if ($product->product->shop_id != $order['shop_id']) return false;

            $name = $product->product->name;
            if ($product->product_attribute_value_ids) {
                $name .= ' (' . $product->full_name . ')';
            }

            $order['items'][$key] = [
                'name' => $name,
                'quantity' => $value['qty'],
                'price' => $value['price'],
                'sum' => $value['price'] * $value['qty']
            ];
            $order['quantity'] += $order['items'][$key]['quantity'];
            $order['sum'] += $order['items'][$key]['sum'];
            if (!empty($product->product->delivery_price))
                $order['tax'] = $product->product->delivery_price;
        }
        if (isset($order['tax']))
            $order['sum'] += $order['tax'];
        else
            $order['tax']=0;
        session()->put('order', $order);
        session()->save();
        session()->forget('cart');
        return true;
    }

    public function setOrderProduct(ProductVariation $productVariation, $qty = 1)
    {
        $name = $productVariation->product->name;
        if ($productVariation->product_attribute_value_ids) {
            $name .= ' (' . $productVariation->full_name . ')';
        }
        $order = [];
        $order['shop_id'] = $productVariation->product->shop_id;
        if (!auth()->guest()) {
            $order['user_id'] = auth()->user()->id;
        }
        $order['quantity'] = $qty;
        $order['tax'] = 0;
        $order['sum'] = $qty * $productVariation->price;
        $order['items'][$productVariation->id] = [
            'quantity' => $qty,
            'price' => $productVariation->price,
            'sum' => $order['sum'],
            'name' => $name,
        ];
        if (!empty($productVariation->product->delivery_price)) {
            $order['tax'] = $productVariation->product->delivery_price;
            $order['sum'] += $order['tax'];
        }
        session()->put('order', $order);
        session()->save();
        return true;
    }

    public function save(array $attributes, $now = true)
    {
        $order = Order::create($attributes);
        if (!$now) {
            $order->address =
                $attributes['city'] . ' ' .
                $attributes['region'] . ' ' .
                $attributes['street'];
            $order->save();
        }
        $order_items = $this->getOrder();
        foreach ($order_items['items'] as $key => $value) {
            OrderItem::create([
                'sum' => $value['sum'],
                'quantity' => $value['quantity'],
                'price' => $value['price'],
                'order_id' => $order->id,
                'product_variation_id' => $key,
            ]);
        }
        return $order;
    }

    public function installment(array $attributes)
    {
        $order = $this->save($attributes, false);
        $order->forceFill(Arr::only($attributes, [
            'installment_card_number',
            'installment_card_date',
            'installment_firstname',
            'installment_middlename',
            'installment_lastname',
            'installment_gender',
            'installment_inn',
            'installment_sn',
            'installment_birthdate',
            'installment_address',
            'installment_issuedby',
            'installment_issueddate'
        ]));
        $order->name = $attributes['installment_firstname'] . ' ' .
            $attributes['installment_middlename'] . ' ' . $attributes['installment_lastname'];

        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $condition = $attributes['installment_condition'];
        if ($attributes['installment_card']) {
            $months = explode(',', $settings['installment_months']);
            $advances = explode(',', $settings['installment_card_advances']);
            $markups = explode(',', $settings['installment_card_markups']);
        } else {
            $months = explode(',', $settings['installment_months']);
            $advances = explode(',', $settings['installment_nocard_advances']);
            $markups = explode(',', $settings['installment_nocard_markups']);
        }
        $order->forceFill([
            'installment_month' => $months[$condition],
            'installment_advance' => $advances[$condition],
            'installment_markup' => $markups[$condition]
        ]);

        $order->installment_passport = Storage::putFile("uploads/installment",
            $attributes['installment_passport']);
        $order->installment_passport_face = Storage::putFile("uploads/installment",
            $attributes['installment_passport_face']);
        $order->installment_passport_address = Storage::putFile("uploads/installment",
            $attributes['installment_passport_address']);
        $order->save();
        return $order;
    }
}
