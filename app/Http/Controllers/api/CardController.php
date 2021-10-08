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
        return $this->success(__('messages.success'),$this->service->all() );
    }


    public function store(CardRequest $request)
    {
        try {
            $result=$this->service->add($request->validated());

            return $this->success(__('payme.confirmation_sent', [
                'attribute' => $result->phone
            ]),$result);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    public function confirm(CardConfirmRequest $request)
    {
        try {
            $this->service->confirm($request->validated());
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
        $request->validate([
            'name' => 'required|string',
        ]);

        $id->update(['name' => $request->name]);
        return $this->success(__('messages.success'));
    }


    public function destroy(Card $id)
    {
        $this->service->removeCard($id->token);
        $id->delete();
        return $this->success(__('messages.success'));
    }
}
