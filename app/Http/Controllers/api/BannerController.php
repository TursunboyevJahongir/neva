<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\Banner\BannerService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * @var BannerService
     */
    private $service;
    use ApiResponser;

    public function __construct(BannerService $service)
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
        $banners = $this->service->all();
     $this->success($banners);
    }

    public function show($model,$id)
    {
        $object = $this->service->object($model,$id);
        $this->success($banners);
    }

}
