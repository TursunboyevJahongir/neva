<?php

namespace App\Http\Requests\Dashboard\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name.ru'        => 'required|string',
            'name.uz'        => 'required|string',
            'content.ru'     => 'required|string',
            'content.uz'     => 'required|string',
            'pickup'         => 'nullable',
            'delivery_price' => 'required|integer',
            'refund'         => 'required|integer',
            'attributes'     => 'nullable',
            'variations.*'   => 'nullable',
            'values.*'       => 'nullable',
            'price.*'        => 'required|integer|min:0',
            'quantity.*'     => 'required|integer|min:0',
            'cover'          => 'nullable|image',
            'img.*'          => 'nullable|image',
            'shop_id'        => 'required|numeric|exists:shops,id',
            'category_id'    => 'required|numeric|exists:categories,id',
            'brand_id'       => 'nullable|numeric|exists:brands,id'
        ];
    }
}
