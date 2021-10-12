<?php

namespace App\Http\Requests\api;

use App\Rules\GenderRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserAddressUpdateRequest extends FormRequest
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
            'apartment' => 'nullable|string',
            'storey' => 'nullable|string',
            'intercom' => 'nullable|string',
            'entrance' => 'nullable|string',
            'landmark' => 'nullable|string',

            'address' => 'nullable|string',
            'lat' => ['required_with:long', 'nullable',
//                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'
            ],
            'long' => ['required_with:lat', 'nullable',
//                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ],

        ];
    }
}
