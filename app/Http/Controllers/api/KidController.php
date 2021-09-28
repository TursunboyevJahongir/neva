<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\api\KidRequest;
use App\Http\Resources\Api\v1\KidResource;
use App\Models\Kids;
use  App\Services\Kid\KidsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class KidController extends ApiController
{
    private $service;

    public function __construct(KidsService $service)
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
        return $this->success(__('messages.success'), KidResource::collection(Auth::user()->kids));
    }

//    /**
//     * Display a listing of the resource.
//     *
//     * @param int $id
//     * @return JsonResponse
//     */
//    public function show(int $id): JsonResponse
//    {
//        return $this->success(__('messages.success'), new KidResource($this->service->one($id)));
//    }

    /**
     * Display a listing of the resource.
     *
     * @param KidRequest $request
     * @return JsonResponse
     */
    public function store(KidRequest $request): JsonResponse
    {
        $kid = $this->service->create($request->validated());
        return $this->success(__('messages.success'), new KidResource($kid));
    }

    /**
     * Display a listing of the resource.
     *
     * @param KidRequest $request
     * @param Kids $id
     * @return JsonResponse
     */
    public function update(KidRequest $request, Kids $id): JsonResponse
    {
        $kid = $this->service->update($request->validated(), $id);
        return $this->success(__('messages.success'), new KidResource($kid));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Kids $id
     * @return JsonResponse
     */
    public function delete(Kids $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->success(__('messages.success'));
    }


}
