<?php

namespace App\Http\Requests\api;

use App\Models\Basket;
use App\Models\ProductVariation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BasketRequest extends FormRequest
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
            'product_id' => 'required|numeric|exists:product_variations,id',//|exists:products,id
            'quantity' => ['required', 'integer',
                function ($attribute, $value, $fail) {
                    $basket = Basket::query()->where('user_id', Auth::id())
                        ->where('product_variation_id', $this->product_id)
                        ->first();
                    $product = ProductVariation::query()
                        ->findOrFail($this->product_id);

                    if ($basket && $value > 0 && ($basket->quantity + $value) > $product->quantity) {
                        $fail(__('messages.not_enough_product'));
                    }

                    if ($basket && $value < 0 && ($basket->quantity + $value) < 0) {
                        $fail(__('messages.basket_not_enough_quantity'));
                    }
                }]
        ];
    }
}
