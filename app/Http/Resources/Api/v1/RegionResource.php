<?php

namespace App\Http\Resources\Api\v1;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
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
         * @var District $this
         */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
//            'children' => DistrictChildrenResource::collection($this->children),
        ];
    }
}
