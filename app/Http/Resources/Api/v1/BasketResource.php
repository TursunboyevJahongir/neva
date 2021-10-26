<?php

namespace App\Http\Resources\Api\v1;

use App\Models\Basket;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BasketResource extends JsonResource
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
         * @var Basket $this
         */
        return [
            "id" => $this->id,
            'product' => new ProductVariationResource($this->product),
            "quantity" => $this->quantity,
            "sum" => $this->sum,
        ];
    }
}
