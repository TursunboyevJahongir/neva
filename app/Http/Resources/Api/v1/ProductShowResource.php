<?php

namespace App\Http\Resources\Api\v1;

use App\Models\Product;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductShowResource extends JsonResource
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
         * @var Product $this
         */
        return [
            "id" => $this->id,
            "sku" => $this->sku,
            "name" => $this->name,
            "content" => $this->content,
            "rating" => $this->rating,
            "min_price" => $this->min_price,
            "max_price" => $this->max_price,
            "percent" => $this->max_percent,
            "images" => $this->image->image_url,
//            "product_attribute" => ProductAttributeValueResource::collection(ProductAttributeValue::query()->whereIn('id', $this->product_attribute_ids)->get()) ?? null,
            "variations" => ProductVariationResource::collection($this->variations),
        ];
    }
}
