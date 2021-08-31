<?php

namespace App\Http\Requests\Dashboard\News;

use Illuminate\Foundation\Http\FormRequest;

class NewsCreateRequest extends FormRequest
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
            'text' => 'required|array',
            'text.*' => 'required|string',
            'image' => 'required|image',
        ];
    }
}
