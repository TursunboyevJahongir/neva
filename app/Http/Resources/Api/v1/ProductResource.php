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
            "id" => $this->id,
            "sku" => $this->sku,
            "name" => $this->name,
            "description" => $this->content,
            "rating" => $this->rating ?? 0,
            "price" => $this->min_price,
            "old_price" => $this->min_old_price,
            "percent" => $this->max_percent,
            "preview" => $this->image->image_url,

        ];
    }
}
