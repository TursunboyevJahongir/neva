<?php

namespace App\Http\Requests\Dashboard\Banner;

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
            'name'              => 'required|array',
            'name.*'            => 'required|string',
            'description'       => 'required|array',
            'description.*'     => 'required|string',
            'link'              => 'required|string',
            'image'             => 'sometimes|image'
        ];
    }
}
