<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Request;
use App\Http\Resources\Api\v1\DistrictChildrenResource;
use App\Http\Resources\Api\v1\DistrictParentResource;
use App\Http\Resources\Api\v1\RegionResource;
use App\Models\District;
use Illuminate\Http\JsonResponse;

class DistrictController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param District|null $id
     * @return JsonResponse
     */
    public function index(Request $request, District $id = null): JsonResponse
    {
        if (isset($id)) {
            return $this->success(__('pages.district'), DistrictParentResource::collection($id->children));
        }
        $orderBy = $request->orderby ?? "name->";
        $sort = $request->sort ?? "ASC";
        $data = District::query()->whereNull('parent_id')->orderBy($orderBy, $sort)->get()->all();
        return $this->success(__('pages.districts'), RegionResource::collection($data));
    }

    public function show(Request $request, District $id): JsonResponse
    {
        return $this->success(__('pages.District'), new DistrictChildrenResource($id));
    }
}
