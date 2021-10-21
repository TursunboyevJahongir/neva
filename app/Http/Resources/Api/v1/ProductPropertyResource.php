<?php

namespace App\Http\Resources\Api\v1;


use App\Models\ProductProperty;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPropertyResource extends JsonResource
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
         * @var ProductProperty $this
         */
        return [
            "id" => $this->id,
            "name" => $this->name,
            "image" => $this->image_url,
            "variations" => ProductVariationResource::collection($this->variations) ?? null,
        ];
    }
}
