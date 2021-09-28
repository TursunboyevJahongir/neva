<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CommentCreateRequest extends FormRequest
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
            'product_id'=> 'required|numeric',//|exists:products,id
            'text' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'images.*' => 'nullable|image',
        ];
    }
}
