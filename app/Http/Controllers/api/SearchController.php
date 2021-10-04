<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;

use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\v1\ProductResource;
use App\Http\Resources\Api\v1\ProductShowResource;
use App\Http\Resources\Api\v1\SearchLikeTestResource;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Services\SearchService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SearchController extends ApiController
{
    public function __construct(private SearchService $service)
    {
    }

    /**
     * @throws Exception
     */
    public function search(string $search, Request $request): JsonResponse
    {
        $search = rtrim($search, " \t.");
        $data = $this->service->search($search, $request);
        $this->service->historySearch($search);
        return $this->success(__('messages.success'), new PaginationResourceCollection($data['products'],
            ProductShowResource::class), $data['append']);
    }

    /**
     * @throws Exception
     */
    public function userSearch(): JsonResponse
    {
        $search = $this->service->userSearch();
        return $this->success(__('messages.success'), SearchLikeTestResource::collection($search));
    }

    /**
     * @throws Exception
     */
    public function userSearchDelete($string): JsonResponse
    {
        $exists = $this->service->userSearchDelete($string);
        if ($exists) {
            $exists->delete();
            return $this->success(__('messages.success'));
        } else
            return $this->error(__('messages.not_found'));

    }

    /**
     * @throws Exception
     */
    public function like(string $search): JsonResponse
    {
        $search = rtrim($search, " \t.");
        $search = $this->service->LikeText($search);
        return $this->success(__('messages.success'), SearchLikeTestResource::collection($search));
    }

    /**
     * @throws Exception
     */
    public function popular(): JsonResponse
    {
        $search = $this->service->Popular();
        return $this->success(__('messages.success'), SearchLikeTestResource::collection($search));
    }
}
