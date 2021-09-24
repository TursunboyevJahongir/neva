<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\DistrictParentResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
         * @var User $this
         */
        return [
            "id" => $this->id,
            "full_name" => $this->full_name ?? null,
            "phone" => $this->phone,
            "birthday" => $this->birthday ? $this->birthday->format('d.m.Y') : null,
            "age" => $this->age ?? null,
            "gender" => $this->gender ?? null,
            "avatar" => $this->avatar->image_url ?? null,
            "status" => $this->status ?? null,
            "email" => $this->email ?? null,
            "address" => $this->address ?? null,
            'district' => $this->district_id ? new DistrictParentResource($this->district) : null,
        ];
    }
}
