<?php

namespace App\Http\Requests\Dashboard\Collection;

use Illuminate\Foundation\Http\FormRequest;

class ActionRequest extends FormRequest
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
            'product' => 'nullable|array',
            'product.*' => 'nullable|string',
        ];
    }
}
