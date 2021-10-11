<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Request;
use App\Models\News;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
         * @var News $this
         */
        return [
            'id' => $this->id,
            'title' => $this->name,
            'description' => $this->sub_description,
            'image' => $this->image->image_url,
        ];
    }
}
