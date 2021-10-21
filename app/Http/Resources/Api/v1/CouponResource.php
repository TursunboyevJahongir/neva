<?php

namespace App\Http\Resources\Api\v1;

use App\Models\UserCoupons;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
         * @var UserCoupons $this
         */
        return [
            "id" => $this->id,
            "name" => $this->coupon->name,
             //"image" => $this->image->image_url,
            "description" => $this->coupon->description,
            "code" => $this->coupon->code,
            "expire" => $this->end_at,
            "used" => $this->used,
        ];
    }
}
