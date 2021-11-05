<?php

namespace App\Http\Requests\api;

use App\Rules\PhoneRule;
use App\Rules\UzbekPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderRequest extends FormRequest
{
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
            'phone' => ['nullable', new PhoneRule()],
            'full_name' => 'nullable|string',
            'quantity' => 'nullable|integer',
            'method' => 'nullable'
        ];
    }
}
