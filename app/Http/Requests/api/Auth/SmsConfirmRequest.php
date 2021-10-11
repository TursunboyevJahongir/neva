<?php

namespace App\Http\Requests\api\Auth;

use App\Http\FormRequest;
use App\Rules\PhoneRule;

class SmsConfirmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => ['required', new PhoneRule()],
            'code' => ['required', 'numeric'],
            'firebase' => 'required|string'
        ];
    }
}
