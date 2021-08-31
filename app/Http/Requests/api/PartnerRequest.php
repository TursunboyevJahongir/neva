<?php

namespace App\Http\Requests\api;

use App\Rules\UzbekPhone;
use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fullname'    => 'required|string',
            'company'     => 'required|string',
            'phone'       => ['required', 'string', new UzbekPhone],
            'address'     => 'required|string',
            'passport'    => 'required|file',
            'license'     => 'required|file',
            'credentials' => 'required|file',
            'resume'      => 'required|string'
        ];
    }
}
