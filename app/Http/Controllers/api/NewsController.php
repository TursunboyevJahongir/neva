<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\v1\NewsResource;
use App\Http\Resources\Api\v1\NewsShowResource;
use App\Models\News;
use App\Services\News\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $service;


    public function __construct(NewsService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $news = $this->service->all();
        return $this->success(__('messages.success'), NewsResource::collection($news));
    }

    public function show(News $id): JsonResponse
    {
       return $this->success(__('messages.success'), new NewsShowResource($id));
    }
}
