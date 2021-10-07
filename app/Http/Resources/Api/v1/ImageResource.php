<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Request;
use App\Models\Address;
use App\Models\Image;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray($request): array
    {
        /**
         * @var Image $this
         */
        return [
//            'id' => $this->id,
            'image_url' => $this->image_url,
        ];
    }
}
