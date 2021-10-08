<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Request;
use App\Models\Banner;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    public bool $preserveKeys = true;
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /**
         * @var Banner $this
         */
        return [
            'object' => $this->object,
            'id' => $this->object_id,
//            'link' => $this->link,
            'image' => $this->image->image_url,
        ];
    }
}
