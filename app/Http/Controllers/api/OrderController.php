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

    public function saveData($request)
    {
        return [
            'name' => $request['name'] ?? Auth::user()->full_name,
            'phone' => $request['phone']?? Auth::user()->phone,
            'location' => $request['location'],
            'city' => $request['city']?? Auth::user()->full_name,
            'region' => $request['region'],
            'address' => $request['street']?? Auth::user()->address,
            'street' => $request['street']?? Auth::user()->full_name,
            'comment' => $request['comment'],
            'delivery' => 'idcourier',
        ];
    }


    /**
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OrderRequest $request)
    {
        abort_if(!auth()->check(), 403);
        $orders = $this->orders->setOrderBasket();
        $this->orders->save($this->saveData($request)+$orders, $orders['items']);
        return $this->success(__('messages.success'));
    }

    public function orderProduct(Request $request,ProductVariation $id)
    {
        $orders = $this->orders->setOrderProduct($id,$request->qty ?? 1);
        $this->orders->save($this->saveData($request)+$orders, $orders['items']);
        return $this->success(__('messages.success'));
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
