<?php


namespace App\Http;


use App\Http\Traits\HasHeaders;
use \Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest
{
    use HasHeaders;
}
