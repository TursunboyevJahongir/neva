<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Request;
use App\Models\Address;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
         * @var Address $this
         */
        return [
//            'id' => $this->id,
            'address' => $this->address,
            'lat' => $this->lat ?? null,
            'long' => $this->long ?? null,

            'apartment' => $this->apartment,
            'storey' => $this->storey,
            'intercom' => $this->intercom,
            'entrance' => $this->entrance,
            'landmark' => $this->landmark
        ];
    }
}
