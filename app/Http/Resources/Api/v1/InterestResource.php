<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\DistrictParentResource;
use App\Models\Interest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterestResource extends JsonResource
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
         * @var Interest $this
         */
        return [
            "id" => $this->id,
//            "name" => $this->name[$request->getDeviceLang()] ?? $this->name[config('app.locale')],
            "name" => $this->name,
//            "description" => $this->description[$request->getDeviceLang()] ?? null,
            "description" => $this->description ?? null
        ];
    }
}
