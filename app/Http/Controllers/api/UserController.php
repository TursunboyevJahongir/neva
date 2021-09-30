<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Request;
use App\Http\Requests\api\UserUpdateRequest;
use App\Http\Resources\Api\v1\UserResource;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{
    /**
     * @var ShopService
     */
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return $this->success(__('messages.success'), new UserResource(auth()->user()));
    }


    public function update(UserUpdateRequest $request): JsonResponse
    {
        $this->service->update($request->validated(), auth()->user());
        return $this->success(__('messages.success'), new UserResource(Auth::user()));
    }
}
