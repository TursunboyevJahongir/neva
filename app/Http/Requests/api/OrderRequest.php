<?php

namespace App\Http\Requests\api;

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
            'phone' => ['nullable','string', new UzbekPhone],
            'name' => 'nullable|string',
            'city' => 'nullable|string',
            'region' => 'nullable|string',
            'street' => 'nullable|string',
            'shop_id' => 'nullable|integer',
            'user_id' => 'nullable|integer',
            'quantity' => 'nullable|integer',
            'sum' => 'nullable|integer',
        ];
    }
}
