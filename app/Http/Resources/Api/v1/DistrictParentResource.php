<?php

namespace App\Http\Resources\Api\v1;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistrictParentResource extends JsonResource
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
            'name' => $this->name[$request->getDeviceLang()] ?? $this->name[config('app.locale')],
            'code' => $this->code,
            'parent' => new DistrictParentResource($this->parent),
        ];
    }
}
