<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\v1\BrandResource;
use App\Services\Brand\BrandService;
use Illuminate\Http\JsonResponse;

class BrandController extends ApiController
{
    /**
     * @var BrandService
     */
    private $service;

    public function __construct(BrandService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $brands = $this->service->all();
        return $this->success(__('messages.success'), BrandResource::collection($brands));

    }
}
