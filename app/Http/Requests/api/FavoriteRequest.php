<?php

namespace App\Http\Requests\api;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FavoriteRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id'
        ];
    }
}
