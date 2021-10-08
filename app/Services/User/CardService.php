<?php

/**
 * Created by Maxamadjonov J.
 * User: Jaxongir
 * Date: 21.01.2021
 */

namespace App\Services\User;

use App\Models\Card;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Integer;

class CardService
{
    public $baseUrl;
    private $client;
    private $front;
    private $merchant_id;
    private $password;

    public function __construct()
    {
        $this->loadConfig();
        $this->client = new Client([
            'base_uri' => $this->baseUrl
        ]);
        $this->front = false;
    }

    private function loadConfig()
    {
        $this->baseUrl = config('payme.url');
        $this->merchant_id = config('payme.id');
        $this->password = config('payme.password');
    }

    public function all()
    {
        $this->front = false;
        $cards = Card::query()->where('user_id', Auth::id())
            ->where('verify', true)->get();
        $cards_list = [];
        foreach ($cards as $card) {
            $check = $this->checkCard($card->token);
            if (isset($check->result) && $check->result->card->verify)
                $cards_list[] = [
                    'id' => $card->id,
                    'name' => $card->name,
                    'number' => $card->hide_number,
                    'expire' => $card->expire,
                    'main' => Auth::user()->main_card === $card->id,
                ];
            else
                $card->update(['verify' => false]);

        }
usort($cards_list, function ($item1, $item2) {
        return $item1['main'] <=> $item2['main'];
    });
        return  $cards_list;
    }

    public function add(array $attributes)
    {
        $this->front = true;
        $result = $this->addCard($attributes['number'], $attributes['expire']);
        if (isset($result->result)) {
            $card = $result->result->card;
            Card::query()->firstOrCreate([
                'user_id' => Auth::id(),
                'name' => $attributes['name'],
                'number' => $attributes['number'],
                'hide_number' => $card->number,
                'expire' => $card->expire,
                'token' => $card->token,
                'verify' => $card->verify,
            ]);
            if (!$card->verify)
                $result = $this->sendCode($card->token);
        }
        if (isset($result->error)) {
            throw new \Exception($result->error->message);
        }
        $result->result->name=$attributes['name'];
        $result->result->number=$card->number;
        $result->result->expire=$card->expire;
        return $result->result;
    }

    public function confirm(array $attributes)
    {
        $this->front = true;
        $token = Card::query()->where('user_id', Auth::id())
            ->where('verify', false)
            ->where('hide_number', $attributes['number'])
            ->first();
        $token->update(['verify' => true]);
        $result = $this->verifyCard($token->token, $attributes['code']);
        if (isset($result->result)) {
            $token->update(['verify' => true]);
        }
        if (isset($result->error)) {
            throw new \Exception($result->error->message);
        }

        return true;
    }

    private function sendRequest($method, $method_api, $params)
    {
        $id = 1;
        $options = [];
        $options['headers']['X-Auth'] = "$this->merchant_id" . (!$this->front ? ":$this->password" : '');
        $options['headers']['Content-Type'] = 'application/json';
        $options['json'] = [
            'id' => $id,
            'method' => $method_api,
            'params' => $params,
        ];
        if (in_array($method, ['GET', 'POST', 'PATCH', 'DELETE', 'PUT'])) {
            $res = $this->client->request($method, '', $options);
            if ($res->getStatusCode() === 200) {
                return json_decode($res->getBody()->getContents());
            }
            throw new \Exception('Bad status code on response');
        } else {
            throw new \Exception('Method not found');
        }
    }

    public function addCard(string $number, string $expire)
    {
        $result = $this->sendRequest('POST',
            'cards.create', [
                'card' => [
                    'number' => $number,
                    'expire' => $expire
                ],
                "save" => true
            ]
        );
        return $result;
    }

    public function sendCode($token)
    {

        $result = $this->sendRequest('POST', 'cards.get_verify_code', [
            'token' => $token
        ]);
        return $result;
    }

    public function verifyCard(string $token, string $code)
    {
        $result = $this->sendRequest('POST',
            'cards.verify',
            [
                'token' => $token,
                'code' => $code
            ],
        );

        return $result;
    }

    public function checkCard(string $token)
    {
        $result = $this->sendRequest('POST',
            'cards.check',
            [
                'token' => $token,
            ],
        );
        return $result;
    }

    public function removeCard(string $token)
    {
        $this->front = false;
        $result = $this->sendRequest('POST',
            'cards.remove',
            [
                'token' => $token,
            ],
        );
        return $result;
    }

    public function receiptsCreate($order_id, $amount)
    {
        $this->front = false;
        $result = $this->sendRequest('POST',
            'receipts.create',
            [
                "amount" => $amount,
                'account' => [
                    'order_id' => $order_id
                ]
            ],
        );
        return $result;
    }

    public function receiptsPay($_id, $token)
    {
        $this->front = false;
        $result = $this->sendRequest('POST',
            'receipts.pay',
            [
                "id" => $_id,
                'token' => $token
            ],
        );
        return $result;
    }

    public function receiptsCancel($_id)
    {
        $this->front = false;
        $result = $this->sendRequest('POST',
            'receipts.cancel',
            [
                "id" => $_id,
            ],
        );
        return $result;
    }

    public function receiptsCheck($_id)
    {
        $this->front = false;
        $result = $this->sendRequest('POST',
            'receipts.check',
            [
                "id" => $_id,
            ],
        );
        return $result;
    }


}
