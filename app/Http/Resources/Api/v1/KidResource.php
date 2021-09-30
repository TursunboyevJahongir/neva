<?php

namespace App\Http\Resources\Api\v1;

use App\Models\Interest;
use App\Models\Kids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KidResource extends JsonResource
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
         * @var Kids $this
         */
        return [
            "id" => $this->id,
            "full_name" => $this->full_name,
            "birthday" => $this->birthday ? $this->birthday->format('d.m.Y') : null,
            "age" => $this->age ?? null,
            "gender" => $this->gender ?? null,
            'interests' => $this->interests ? InterestResource::collection(Interest::query()->whereIn('id', $this->interests)->get()) : null,
        ];
    }
}
