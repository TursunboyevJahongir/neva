<?php

namespace App\Http\Requests\api;

use App\Rules\GenderRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends FormRequest
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
     * Prepare the data for validation.
     * prepareForValidation bu narsa validatsiyadan oldin biror datani o'zgartirib keyin jo'natish uchun kerak bo'ladi
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'interests' => json_decode($this->interests,true),
        ]);
    }

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
            'interests.*' => 'nullable|exists:interests,id',
        ];
    }
}
