<?php

namespace App\Http\Requests\api;

use App\Http\FormRequest;
use App\Rules\PhoneRule;

class CardConfirmRequest extends FormRequest
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
            'number' => 'required|max:16|min:16|exists:cards,hide_number',
            'code' => ['required', 'numeric']
        ];
    }
}
