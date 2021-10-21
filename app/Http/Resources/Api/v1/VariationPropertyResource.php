<?php

namespace App\Http\Resources\Api\v1;


use App\Models\VariationProperty;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariationPropertyResource extends JsonResource
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
         * @var VariationProperty $this
         */
        return [
            "id" => $this->id,
            "name" => $this->name,
            "image" => $this->image_url,
            "properties" => ProductVariationResource::collection($this->properties) ?? null,
        ];
    }
}
