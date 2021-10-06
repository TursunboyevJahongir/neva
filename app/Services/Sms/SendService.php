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
                //return json_decode($res->getBody()->getContents(), true);
                return $res->getBody()->getContents();
            }
            throw new \Exception('Bad status code on response');
        } else {
            throw new \Exception('Method not found');
        }
    }

    public function sendSMS(string $phone,string $message)
    {
        $res = $this->sendRequest('POST', '/http2sms', [
           'query'=> ['login' => $this->login,
            'pwd' => $this->password,
            'CgPN' => $this->sender,
            'CdPN' => $phone,
            'text' => $message,]
        ]);
        return $res;
    }

    public function sendFirebase($fcm_token,$message)
    {
        $this->client = new Client([
            'base_uri' => 'https://fcm.googleapis.com/fcm/send'
        ]);
        $this->token=null;
        $key = 'AAAAsmxfoE8:APA91bHgIlZCyLnq0aj2wBN5x-zWBnY-ddZ1no_VsIiZ0TlO2Xd5qpwEfw1vyc_eiqvRp1dDyvmd2gBmVTRVwlxJ108qPSgxEFzGS6O5oC2j-r2bL2-p-gTMA9ko5aoXYVgUt6XW0P9Q';
        $res = $this->sendRequest('POST', '', [
            "to" => $fcm_token,
            "notification" => [
                "body" => $message,
                "title" => "Neva",
                "icon" => "ic_launcher"
            ],
            "data" => [
                "body" => $message,
                "title" => "Neva",
                "icon" => "ic_launcher"
            ],
            'headers'=>[
                'Content-Type'=>'application/json',
                'Authorization'=>'key='.$key
            ]
        ]);
        return $res;
    }
}
