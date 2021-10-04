<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;

use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\v1\ProductResource;
use App\Http\Resources\Api\v1\ProductShowResource;
use App\Http\Resources\Api\v1\SearchLikeTestResource;
use App\Models\Product;
use App\Services\Product\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductController extends ApiController
{
    public function __construct(private ProductService $service)
    {
    }
    /**
     * Display the specified resource.
     *
     * @param Product $id
     * @return JsonResponse
     */
    public function show(Product $id): JsonResponse
    {
        $this->service->historyView($id->id);
        return $this->success(__('messages.success'), new ProductShowResource($id));
    }

}
