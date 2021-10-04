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
    private $orders;
    private $invoice;

    /**
     * OrderController constructor.
     * @param OrderService $orderService
     * @param InvoiceService $invoiceService
     * @param PageService $pageService
     */
    public function __construct(OrderService $orderService, InvoiceService $invoiceService, PageService $pageService)
    {
        $this->invoice = $invoiceService;
        $this->orders = $orderService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!auth()->check(), 403);
        $orderList = $this->orders->getOrder();
        $shop = Shop::find($orderList['shop_id']);
        return view('front.order', compact('orderList', 'shop'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!auth()->check(), 403);

        if (request()->has('cart')) {
            $order = $this->orders->setOrderCart();

        }
        if (request()->has('product')) {
            $this->orders->setOrderProduct(
                ProductVariation::find(request()->input('product')),
                request()->input('qty')
            );

        }
        return [];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
