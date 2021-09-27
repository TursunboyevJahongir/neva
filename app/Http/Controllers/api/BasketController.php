<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\BasketProductDeleteRequest;
use App\Http\Requests\api\BasketRequest;
use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\v1\BasketResource;
use App\Models\Basket;
use App\Services\Basket\BasketService;
use Illuminate\Http\Request;

class BasketController extends ApiController
{

    private $service;

    public function __construct(BasketService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function index(Request $request)
    {
        $size = $request->per_page ?? 10;
        $orderBy = $request->orderby ?? "created_at";
        $sort = $request->sort ?? "DESC";
        $baskets = $this->service->all($orderBy, $sort, $size);
        return $this->success(__('pages.documents'), new PaginationResourceCollection($baskets['basket'], BasketResource::class), $baskets['append']);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function store(BasketRequest $request)
    {
        $this->service->cart($request->validated());
        return $this->success(__('messages.success'));
    }

    public
    function delete(BasketProductDeleteRequest $request)
    {
        Basket::whereIn('id', $request->basket_id)->delete();
        return $this->success(__('messages.success'));
    }


}
