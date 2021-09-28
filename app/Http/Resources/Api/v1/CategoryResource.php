<?php

namespace App\Http\Resources\Api\v1;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * @var Category $this
         */
        return [
            "id" => $this->id,
             "name" => $this->name
        ];
    }
}
