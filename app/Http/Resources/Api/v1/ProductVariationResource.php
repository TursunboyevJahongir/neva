<?php

namespace App\Http\Resources\Api\v1;

use App\Models\Basket;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariationResource extends JsonResource
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
         * @var ProductVariation $this
         */
        return [
            "id" => $this->id,
            "name" => $this->name,
            "image" => $this->image_url,
            "properties" => VariationPropertyResource::collection($this->properties) ?? null,
        ];
    }
}
