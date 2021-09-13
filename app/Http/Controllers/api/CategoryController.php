<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Category\CategoryService;
use App\Services\Product\ProductService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @var BannerService
     */
    private $service;
    use ApiResponser;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = $this->service->all();
        return $this->success($categories);
    }

    public function show(Category $category)
    {
        $data=new ProductService();
        $data=$data->render($category);
        $this->success($data);
    }
}
