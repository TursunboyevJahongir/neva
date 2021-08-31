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
    private $client;
    private $login;
    private $password;
    private $sender;

    public function __construct()
    {
        $this->loadConfig();
        $this->client = new Client([
            'base_uri' => $this->baseUrl
        ]);
    }

    private function loadConfig()
    {
        $this->baseUrl = config('sms.api_url');
        $this->login = config('sms.login');
        $this->sender = config('sms.sender');
        $this->password = config('sms.password');
        $this->token = config('sms.token');
    }



    private function sendRequest($method, $uri, $options = [])
    {
        if ($this->token) {
            $options['headers']['Authorization'] = "Bearer {$this->token}";
            $options['headers']['Content-Type'] = 'application/json';
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
        $res = $this->sendRequest('POST', '/', [
            'login' => $this->login,
            'pwd' => $this->password,
            'CgPN' => $this->sender,
            'CdPN' => $phone,
            'text' => $message,
        ]);
        return $res;
    }
}