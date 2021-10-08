<?php

namespace App\Http\Resources\Api\v1;

use App\Models\Intro;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IntroResource extends JsonResource
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
         * @var Intro $this
         */
        return [
            "title" => $this->title,
            "description" => $this->description,
            "images" => ImageResource::collection($this->images),
        ];
    }
}
