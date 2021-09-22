<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ProductController extends ApiController
{

    /**
     * @var BannerService
     */
    private $service;
    use ApiResponser;

    public function __construct(ProductService $service)
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
        $products = $this->service->all();
        return $this->success($products);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $id)
    {
        return $this->success($id->load('images:url,imageable_id'));
    }

}
