<?php

namespace App\Http\Requests\api;

use App\Rules\GenderRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class KidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name' => 'required|string',
            'birthday' => 'nullable|date',
            'gender' => ['nullable', new GenderRule()],
            'interests.*' => 'nullable|exists:interests,id',
        ];
    }
}
