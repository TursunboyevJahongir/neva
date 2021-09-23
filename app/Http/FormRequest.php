<?php


namespace App\Http;


use App\Http\Traits\HasHeaders;

class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    use HasHeaders;
}
