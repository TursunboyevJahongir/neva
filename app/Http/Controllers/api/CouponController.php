<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\v1\CouponResource;
use App\Models\Coupon;
use App\Services\Coupon\CouponService;
use Illuminate\Http\Request;

class CouponController extends ApiController
{
    private $service;

    public function __construct(CouponService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons=$this->service->all();
        return $this->success(__('messages.success'), CouponResource::collection($coupons));
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        //
    }


}
