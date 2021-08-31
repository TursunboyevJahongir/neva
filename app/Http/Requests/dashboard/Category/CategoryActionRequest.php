<?php

namespace App\Http\Requests\Dashboard\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryActionRequest extends FormRequest
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
            'parent_id'     => 'nullable|exists:categories,id',
            'description'   => 'nullable|array',
            'description.*'   => 'nullable|string',
            'image' => 'nullable|image',
            'icon' => 'nullable|image',
            'active'        => 'integer'
        ];
    }
}
