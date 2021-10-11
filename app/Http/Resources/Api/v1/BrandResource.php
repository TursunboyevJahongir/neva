<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Request;
use App\Models\Shop;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /**
         * @var Shop $this
         */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image->image_url,
        ];
    }
}
