<?php

namespace App\Http\Resources\Api\v1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            "name" => $this->name,
            "sku" => $this->sku,
            "description" => $this->content,
            "rating" => $this->rating,
            "price" => $this->min_price,
            "preview" => $this->image->image_url,

        ];
    }
}
