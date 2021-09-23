<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\Banner\BannerService;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends ApiController
{
    /**
     * @var BannerService
     */
    private $service;

    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $banners = $this->service->all();
        return $this->success($banners);
    }

    public function show($model, $id = 1)
    {
        $object = $this->service->object($model, $id);

        $this->success($object);
    }

}
