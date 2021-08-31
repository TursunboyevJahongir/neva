<?php

namespace App\Http\Requests\Dashboard\Page;

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
            'name'      => 'required|array',
            'name.*'    => 'required|string',
            'content'   => 'required|array',
            'content.*' => 'required|string'
        ];
    }
}
