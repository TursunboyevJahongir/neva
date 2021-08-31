<?php

namespace App\Http\Requests\Dashboard\Discount;

use Illuminate\Foundation\Http\FormRequest;

class DiscountCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|array',
            'name.*' => 'required|string',
            'discount' => 'required|string',
            'product' => 'required|numeric|exists:products,id',
            'input' => 'required|integer|min:0',
            'start' => 'required|date',
            'end' => 'required|date',

        ];
    }
}
