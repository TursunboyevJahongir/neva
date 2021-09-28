<?php

namespace App\Http\Resources\Api\v1;

use App\Models\Basket;
use App\Models\ProductAttributeValue;
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
            "product" => [
                "id" => $this->product->product->id,
                'name' => $this->product->product->name,
                'price' => $this->product->price,
                'image' => $this->product->image->image_url,
                'attributes' => ProductAttributesResource::collection(ProductAttributeValue::whereIn('id', $this->product->product_attribute_value_ids)->get()),
            ],
            "quantity" => $this->quantity,
        ];
    }
}
