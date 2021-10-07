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
            'interests' => json_decode($this->interests),
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
//            'district_id' => 'nullable|exists:districts,id',
            'interests.*' => 'nullable|exists:interests,id',
            'address' => 'nullable|string',
            'lat' => ['required_with:long', 'nullable',
//                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'
            ],
            'long' => ['required_with:lat', 'nullable',
//                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ]
        ];
    }
}
