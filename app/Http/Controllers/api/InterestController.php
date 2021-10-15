<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Request;
use App\Http\Resources\Api\v1\InterestResource;
use App\Models\Interest;
use Illuminate\Http\JsonResponse;

class InterestController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request): JsonResponse
    {
        $orderBy = $request->orderby ?? "name->" . $request->getDeviceLang();
        $sort = $request->sort ?? "ASC";
        $Customer = Interest::query()->orderBy($orderBy, $sort)->get()->all();
        return $this->success(__('pages.success'), InterestResource::collection($Customer));
    }
}
