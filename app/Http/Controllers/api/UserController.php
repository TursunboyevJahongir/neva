<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\UserUpdateRequest;
use App\Models\Kids;
use App\Models\User;
use  App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (auth()->user()->active)
            return auth()->user();
        else
            return response()->json([
                'message' => 'Пользователь заблокирован'
            ], 403);
    }


    public function update(UserUpdateRequest $request)
    {
        $user=auth()->user();
        return $this->service->update($request->validated(), $user);

    }


}
