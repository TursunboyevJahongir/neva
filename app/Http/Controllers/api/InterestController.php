<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\v1\InterestResource;
use App\Models\Interest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InterestController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request): JsonResponse
    {
        $orderBy = $request->orderby ?? "name->$request->getDeviceLang()";
        $sort = $request->sort ?? "ASC";
        $Customer = Interest::query()->orderBy($orderBy, $sort)->get()->all();
        return $this->success(__('pages.success'), InterestResource::collection($Customer));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Basket  $basket
     * @return Response
     */
    public function show( $basket)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request,  $basket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Basket  $basket
     * @return Response
     */
    public function destroy( $basket)
    {
        //
    }
}
