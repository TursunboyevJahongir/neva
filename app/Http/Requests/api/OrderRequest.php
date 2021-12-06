<?php

namespace App\Http\Requests\api;

use App\Models\ProductProperty;
use App\Rules\PhoneRule;
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
            'product_id' => 'required,exists:variation_properties,id',
            'quantity' => ['required', 'integer',
                function ($attribute, $value, $fail) {
                    $product = ProductProperty::query()->find($this->product_id);

                    if ($product->quantity < $value) {
                        $fail(__('messages.not_enough_product'));
                    }
                }],
            'phone' => ['nullable', new PhoneRule()],
            'full_name' => 'nullable|string',
            'quantity' => 'nullable|integer',
            'method' => 'nullable'
        ];
    }
}
