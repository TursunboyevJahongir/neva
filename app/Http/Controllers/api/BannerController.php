<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\v1\BannerResource;
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
        return $this->success(__('messages.success'), BannerResource::collection($banners));

    }

    public function show(Request $request,Banner $id)
    {
        $data=null;
        $object = $this->service->object($id->object, $id->id,$data,$request);

        return $this->success(__('messages.success'),$object);
    }

}
