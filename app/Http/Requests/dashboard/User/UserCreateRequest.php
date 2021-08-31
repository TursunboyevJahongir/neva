<?php

namespace App\Http\Requests\Dashboard\User;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'full_name' => 'nullable|string',
            'role_id' => 'required|numeric|exists:App\Models\Role,id',
            'password' => 'required',
            'image'         => 'nullable|image',
            'blocked'         => 'nullable|in:0,1',
            'gender'         => 'nullable|string',
        ];
    }
}
