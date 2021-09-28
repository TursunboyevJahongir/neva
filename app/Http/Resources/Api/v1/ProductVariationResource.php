<?php

namespace App\Http\Resources\Api\v1;

use App\Models\Basket;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariationResourceResource extends JsonResource
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
            "product" => new ProductResource($this->product),
            "quantity" => $this->quantity,
        ];
    }
}
