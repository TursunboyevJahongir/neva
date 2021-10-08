<?php

namespace App\Http\Resources\Api\v1;

use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SelectedAttributesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this['attribute'],
            "name" => ProductAttribute::query()->find($this['attribute'])->name,
            "values" => SelectedAttributeValueResource::collection(ProductAttributeValue::query()->whereIn('id', $this['values'])->get()),
        ];
    }
}
