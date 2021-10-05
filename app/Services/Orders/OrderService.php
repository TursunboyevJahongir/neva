<?php

namespace App\Services\Orders;

use App\Models\Basket;
use App\Models\Setting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariation;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class OrderService
{
    public function all($user_id = false)
    {
        return Order::query()
            ->latest('id')
            ->where('user_id',$user_id)
            ->paginate(config('app.per_page'));
    }

    public function calcDelivery($items)
    {
        foreach ($items as $item)
            $suppiler[] = $item['shop'];

        $suppiler = array_unique($suppiler);
        return Shop::query()->find($suppiler)->sum('delivery_price');
    }

    public function setOrderBasket()
    {
        $order = [];
        $order['user_id'] = Auth::id();
        $order['quantity'] = 0;
        $order['total_price'] = 0;
        $basket = Basket::query()
            ->where('user_id', Auth::id())->get()->load('product');
        foreach ($basket as  $item) {
            $productVariation = $item->product;
            $product = $productVariation->product;
            $name = $productVariation->name;
            if ($product->product_attribute_value_ids) {
                $name .= ' (' . $product->full_name . ')';
            }

            $order['items'][$item->id] = [
                'name' => $name,
                'sku' => $product->sku,
                'quantity' => $item['quantity'],
                'price' => $productVariation->price,
                'sum' => $productVariation->price * $item['quantity'],
                'shop' => $product->shop_id
            ];
            $order['quantity'] += $order['items'][$item->id]['quantity'];
            $order['total_price'] += $order['items'][$item->id]['sum'];
        }
        $order['price_delivery'] = $this->calcDelivery($order['items']);
        $order['total_price'] += $order['price_delivery'];

        return $order;
    }

    public function setOrderProduct(ProductVariation $productVariation, $qty = 1)
    {
        $name = $productVariation->product->name;
        if ($productVariation->product_attribute_value_ids) {
            $name .= ' (' . $productVariation->full_name . ')';
        }
        $order = [];
        $order['user_id'] = Auth::id();
        $order['quantity'] = $qty;
        $order['price_delivery'] = 0;
        $order['total_price'] = $qty * $productVariation->price;
        $order['items'][$productVariation->id] = [
            'name' => $name,
            'sku' => $productVariation->product->sku,
            'shop' => $productVariation->product->shop_id,
            'quantity' => $qty,
            'price' => $productVariation->price,
            'sum' => $order['sum'],
        ];
        $order['price_delivery'] = $this->calcDelivery($order['items']);
        $order['total_price'] += $order['price_delivery'];
        return $order;
    }

    public function save(array $attributes, $order_items)
    {
        $order = Order::create($attributes);
        $order->address =
            $attributes['city'] . ' ' .
            $attributes['region'] . ' ' .
            $attributes['street'];
        $order->save();
        foreach ($order_items as $key => $value) {
            OrderItem::create([
                'sku' =>$value['sku'],
                'sum' => $value['sum'],
                'quantity' => $value['quantity'],
                'price' => $value['price'],
                'order_id' => $order->id,
                'product_variation_id' => $key,
                'shop_id' => $value['shop'],
            ]);
        }
        Basket::query()
            ->where('user_id', Auth::id())->delete();
        return $order;
    }
}
