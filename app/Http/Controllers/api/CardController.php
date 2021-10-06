<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\api\CardConfirmRequest;
use App\Http\Requests\api\CardRequest;
use App\Models\Card;
use App\Services\User\CardService;
use Illuminate\Http\Request;

class CardController extends ApiController
{

    private $service;

    public function __construct(CardService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        
    }


    public function store(CardRequest $request)
    {
        try {
            return $this->success(__('payme.confirmation_sent', [
                'attribute' => $this->service->add($request->validated())->phone
            ]));
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    public function confirm(CardConfirmRequest $request)
    {
        try {
            return $this->success(__('payme.verify'));
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    public function show(Card $id)
    {
        return $this->success(__('messages.success'),[
            'name'=>$id->name,
            'number'=>$id->hide_number,
        ]);
    }


    public function update(Request $request, Card $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);
        $id->update($request->name);
    }


    public function destroy(Card $id)
    {
        $this->service->removeCard($id->token);
        $id->delete();
        return $this->success(__('messages.success'));
    }
}
