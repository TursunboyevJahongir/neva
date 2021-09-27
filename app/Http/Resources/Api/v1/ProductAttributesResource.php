<?php

namespace App\Http\Resources\Api\v1;

use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributesResource extends JsonResource
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
         * @var ProductAttributeValue $this
         */
        return [
            "name" => $this->attribute->name,
            "value" => $this->name,
        ];
    }
}
