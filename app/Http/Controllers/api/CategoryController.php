<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\v1\ProductResource;
use App\Models\Category;
use App\Services\Category\CategoryService;
use App\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function index(Request $request): JsonResponse
    {
        $categories = $this->service->all();
        return $this->success(__('messages.success'), $categories);
    }

    public function show(Category $id)
    {
        $data = new ProductService();
        $data = $data->render($id);
        return $this->success(__('pages.RoadReport'), new PaginationResourceCollection($data['products'],
            ProductResource::class),$data['appends']);
    //   return $this->success(__('messages.success'), new PaginationResourceCollection());
    }
}
