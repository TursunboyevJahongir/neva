<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;

use App\Http\Requests\api\SearchRequest;
use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\v1\ProductResource;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ProductController extends ApiController
{
    public function __construct(private ProductService $service)
    {
    }

    /**
     * @throws Exception
     */
    public function search(string $search, Request $request): JsonResponse
    {
        $data = $this->service->search($search, $request);
        return $this->success(__('messages.success'), new PaginationResourceCollection($data['products'],
            ProductResource::class), $data['append']);
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
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $id)
    {
        return $this->success($id->load('images:url,imageable_id'));
    }

}
