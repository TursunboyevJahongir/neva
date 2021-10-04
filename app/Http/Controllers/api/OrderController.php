<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Services\Orders\OrderService;
use App\Services\Page\PageService;
use App\Services\Payments\InvoiceService;
use Illuminate\Http\Request;

class OrderController extends ApiController
{

    /**
     * OrderController constructor.
     * @param OrderService $orders
     */
    public function __construct(private OrderService $orders)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!auth()->check(), 403);
        $orderList = $this->orders->all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!auth()->check(), 403);
        $orders = $this->orders->setOrderBasket();
        $this->orders->save([
            'city' => 'fsrf',
            'region' => 'fsrf',
            'street' => 'fsrf',
            'delivery' => 'idcourier',
        ]+$orders, $orders['items']);
        return [];
    }

    public function orderProduct(ProductVariation $id)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
