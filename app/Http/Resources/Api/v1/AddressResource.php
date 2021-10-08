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
            'lat' => $this->lat,
            'long' => $this->long,
        ];
    }
}
