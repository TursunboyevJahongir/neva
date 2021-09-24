<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Basket;
use Illuminate\Http\Request;

class BasketController extends ApiController
{

    private $service;

    public function __construct(BasketService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = $this->service->all();
        return $this->success(__('messages.success'), $comments);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BasketCreateRequest $request)
    {
        $comment = $this->service->create($request->validated());
        return $this->success(__('messages.success'), $comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Basket  $basket
     * @return \Illuminate\Http\Response
     */
    public function show(Basket $id)
    {
        return $this->success(__('messages.success'), $id->load('images:url,imageable_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Basket  $basket
     * @return \Illuminate\Http\Response
     */
    public function update(BasketUpdateRequest $request, Basket $id)
    {
        $comment = $this->service->update($request->validated(), $id);
        return $this->success(__('messages.success'), $comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Basket  $basket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Basket $id)
    {
        $this->authorize('delete', 'comment');
        $comment = $this->service->delete($comment);
        return $this->success(__('messages.success'), $comment);
    }
}
