<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Request;
use App\Models\News;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsShowResource extends JsonResource
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
            'title' => $this->name,
            'description' => $this->description,
            'image' => $this->image->image_url,
        ];
    }
}
