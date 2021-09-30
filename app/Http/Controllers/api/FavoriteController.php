<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\api\FavoriteRequest;
use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\v1\FavoriteResource;
use App\Models\Favorite;
use App\Services\Favorite\FavoriteService;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public
    function index(Request $request)
    {
        $size = $request->per_page ?? 10;
        $orderBy = $request->orderby ?? "created_at";
        $sort = $request->sort ?? "DESC";
        $favorites = $this->service->all($orderBy, $sort, $size);
        return $this->success(__('messages.success'), new PaginationResourceCollection($favorites, FavoriteResource::class));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function store(FavoriteRequest $request)
    {
        $this->service->add($request->validated());
        return $this->success(__('messages.success'));
    }

    public
    function delete(FavoriteRequest $request)
    {
        Favorite::whereIn('id', $request->id)->delete();
        return $this->success(__('messages.success'));
    }

}
