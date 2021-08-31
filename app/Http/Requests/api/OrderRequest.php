<?php

namespace App\Http\Requests\api;

use App\Rules\UzbekPhone;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => ['required', 'string', new UzbekPhone],
            'name' => 'required|string',
            'city' => 'nullable|string',
            'region' => 'nullable|string',
            'street' => 'nullable|string',
            'shop_id' => 'nullable|integer',
            'user_id' => 'nullable|integer',
            'quantity' => 'nullable|integer',
            'sum' => 'nullable|integer',
            'method' => 'nullable'
        ];
    }
}
