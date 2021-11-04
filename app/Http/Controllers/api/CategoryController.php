<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Request;
use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\v1\CategoryResource;
use App\Http\Resources\Api\v1\ProductResource;
use App\Models\Category;
use App\Services\Category\CategoryService;
use App\Services\Product\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;

class CategoryController extends ApiController
{

    private $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function parents(Request $request): JsonResponse
    {
        $orderBy = $request->orderby ?? "position";
        $sort = $request->sort ?? "DESC";
        $lang = $request->getDeviceLang();
        $categories = $this->service->Parents($orderBy, $sort, $lang);
        return $this->success(__('messages.success'), CategoryResource::collection($categories));
    }

    /**
     * @param Category $id
     * @param ProductService $data
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function show(Category $id, ProductService $data, Request $request): JsonResponse
    {
        $data = $data->categoryProducts($id, $request);
        return $this->success(__('messages.success'), new PaginationResourceCollection($data['products'],
            ProductResource::class), $data['append']);
    }
}
