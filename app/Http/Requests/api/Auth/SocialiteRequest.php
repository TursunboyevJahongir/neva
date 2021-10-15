<?php

namespace App\Http\Requests\api\Auth;

use App\Enums\SocialiteEnum;
use App\Rules\PhoneRule;
use App\Rules\UzbekPhone;
use Illuminate\Foundation\Http\FormRequest;

class SocialiteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'access_token' => 'required',
            'socialite' => 'required|string|in:' . implode(',', SocialiteEnum::toArray()),
        ];
    }
}
