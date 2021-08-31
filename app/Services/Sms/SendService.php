<?php

/**
 * Created by Maxamadjonov J.
 * User: Jaxongir
 * Date: 21.01.2021
 */

namespace App\Services\Sms;

use GuzzleHttp\Client;

class SendService
{
    public $baseUrl;
    private $token;
    private $tokenLifetime;
    private $client;
    private $email;
    private $password;
    private $sender;

    public function __construct()
    {
        $this->loadConfig();
        $this->client = new Client([
            'base_uri' => $this->baseUrl
        ]);
        $this->login();
    }

    private function loadConfig()
    {
        $this->baseUrl = config('sms.api_url');
        $this->tokenLifetime = config('sms.token_lifetime');
        $this->email = config('sms.email');
        $this->password = config('sms.password');
    }

    private function login()
    {
        $this->token = cache()->remember('sms_auth_token', $this->tokenLifetime, function () {
            $res = $this->sendRequest('POST', 'auth/login', [
                'form_params' => [
                    'email' => $this->email,
                    'password' => $this->password
                ]
            ]);
            return $res['data']['token'];
        });

    }

    private function sendRequest($method, $uri, $options = [])
    {
        if ($this->token) {
            $options['headers']['Authorization'] = "Bearer {$this->token}";
        }
        if (in_array($method, ['GET', 'POST', 'PATCH', 'DELETE', 'PUT'])) {
            $res = $this->client->request($method, $uri, $options);
            if ($res->getStatusCode() === 200) {
                return json_decode($res->getBody()->getContents(), true);
            }
            throw new \Exception('Bad status code on response');
        } else {
            throw new \Exception('Method not found');
        }
    }

    public function sendSMS($phone, $message)
    {
        $res = $this->sendRequest('POST', 'message/sms/send', [
            'form_params' => [
                'mobile_phone' => $phone,
                'message' => $message,
                //'from' => $this->sender
            ],
        ]);
        return $res;
    }
}