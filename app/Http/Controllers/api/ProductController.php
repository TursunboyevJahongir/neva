<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;

use App\Http\Request;
use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\v1\ProductAttributesResource;
use App\Http\Resources\Api\v1\ProductAttributeValueResource;
use App\Http\Resources\Api\v1\ProductResource;
use App\Http\Resources\Api\v1\ProductShowResource;
use App\Http\Resources\Api\v1\ProductVariationResource;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariation;
use App\Services\Product\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;


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
//        return $this->success(__('messages.success'), ProductAttributesResource::collection(ProductAttribute::query()->get()));
    }

    /**
     * Display the specified resource.
     *
     * @param Product $id
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function similar(Product $id, Request $request): JsonResponse
    {
        $size = $request->per_page ?? 10;
        $lang = $request->getDeviceLang();
        $data = $this->service->similar($id, $size, $lang);
        return $this->success(__('messages.success'), new PaginationResourceCollection($data, ProductResource::class));
    }

}
