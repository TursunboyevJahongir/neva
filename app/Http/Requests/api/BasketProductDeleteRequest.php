<?php

namespace App\Http\Requests\api;

use App\Models\Basket;
use App\Models\ProductVariation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BasketProductDeleteRequest extends FormRequest
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
            'basket_id.*' => 'required|exists:baskets,id',
        ];
    }
}
