<?php

namespace App\Http\Requests\Dashboard\Shop;

use Illuminate\Foundation\Http\FormRequest;

class ShopCreateRequest extends FormRequest
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
            'description'   => 'nullable|array',
            'description.*'   => 'nullable|string',
            'user_id'       => 'required|numeric',//|exists:users,id,deleted_at,NULL
            'image'         => 'required|image',
            'active'         => 'required|in:0,1',
        ];
    }
}
