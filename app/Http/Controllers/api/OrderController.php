<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\api\OrderRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Services\Orders\OrderService;
use App\Services\Page\PageService;
use App\Services\Payments\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function store(OrderRequest $request)
    {
        abort_if(!auth()->check(), 403);
        $orders = $this->orders->setOrderBasket();
        $this->orders->save([
            'name' => $request['name'] ?? Auth::user()->full_name,
            'phone' => $request['phone']?? Auth::user()->phone,
            'location' => $request['location'],
            'city' => $request['city']?? Auth::user()->full_name,
            'region' => $request['region'],
            'address' => $request['street']?? Auth::user()->address,
            'street' => $request['street']?? Auth::user()->full_name,
            'comment' => $request['comment'],
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
