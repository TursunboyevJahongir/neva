<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;

use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\v1\ProductResource;
use App\Http\Resources\Api\v1\ProductShowResource;
use App\Models\Product;
use App\Services\Product\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


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
            ProductShowResource::class), $data['append']);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $id
     * @return JsonResponse
     */
    public function show(Product $id): JsonResponse
    {
        return $this->success(__('messages.success'),new ProductShowResource($id));
    }

}
