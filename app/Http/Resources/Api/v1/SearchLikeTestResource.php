<?php

namespace App\Http\Resources\Api\v1;

use App\Models\Basket;
use App\Models\HistorySearch;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchLikeTestResource extends JsonResource
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
         * @var HistorySearch $this
         */
        return [
            "text" => $this->query,
            "count" => $this->count,
        ];
    }
}
