<?php

namespace App\Http\Requests\api;

use App\Rules\GenderRule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name' => 'nullable|string',
            'birthday' => 'nullable|date',
            'avatar' => 'nullable|image|max:1000',
            'email' => 'nullable|email',
            'gender' => ['nullable', new GenderRule()],
            'address' => 'nullable|string',
            'district_id' => 'nullable|exists:districts,id',
        ];
    }
}
