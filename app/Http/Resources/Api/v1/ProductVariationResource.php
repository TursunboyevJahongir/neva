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
            "quantity" => $this->quantity,
            "old_price" => $this->product->moneyFormatter($this->old_price),
            "price" => $this->product->moneyFormatter($this->price),
            "image" => $this->image->image_url,
            "percent" => $this->percent ?? null,
            "product_attribute" => SelectedAttributesResource::collection($this->product_attributes) ?? null,
        ];
    }
}
