<?php

namespace App\Http\Requests\api;

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
            'email' => 'nullable|email',
            'birthday' => 'nullable|date',
            'kids' => 'nullable|array',
            'address' => 'nullable|string',
            'district' => 'nullable|string',
            'phone' => 'nullable|string',
            'full_name' => 'nullable|string',
            'password' => 'nullable',
            'image' => 'nullable|image',
            'gender' => 'nullable|string',
        ];
    }
}
