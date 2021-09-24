<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Services\Category\CategoryService;
use App\Services\Product\ProductService;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $this->success(__('messages.success'), $data);
    }
}
