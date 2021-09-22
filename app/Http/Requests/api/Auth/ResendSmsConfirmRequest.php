<?php

namespace App\Http\Requests\api\Auth;


use App\Http\FormRequest;
use App\Rules\PhoneRule;

class ResendSmsConfirmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', new PhoneRule(), 'exists:sms_confirms,phone']
        ];
    }
}
