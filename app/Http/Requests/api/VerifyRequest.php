<?php

namespace App\Http\Requests\api;

use App\Rules\UzbekPhone;
use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => ['required', 'string', new UzbekPhone],
            'verify_code' => 'required|numeric|min:10000|max:99999'
        ];
    }
}
