<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\api\FavoriteRequest;
use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\v1\FavoriteResource;
use App\Http\Resources\Api\v1\ProductResource;
use App\Models\Favorite;
use App\Services\Favorite\FavoriteService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FavoriteController extends ApiController
{
    private $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        $size = $request->per_page ?? 10;
        $favorites = $this->service->all($size);
        return $this->success(__('messages.success'), new PaginationResourceCollection($favorites, ProductResource::class));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param FavoriteRequest $request
     * @return JsonResponse
     */
    public function store(FavoriteRequest $request): JsonResponse
    {
        $this->service->add($request->validated());
        return $this->success(__('messages.success'));
    }


}
