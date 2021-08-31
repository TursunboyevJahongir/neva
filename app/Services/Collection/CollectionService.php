<?php

namespace App\Services\Collection;

use App\Models\Collection;
use App\Models\Product;

class CollectionService
{
    public function all()
    {
        return Collection::query()
            ->oldest('id')
            ->get();
    }

    public function update(array $attributes, Collection $collection)
    {
        $products = Product::all();
        if (!empty($attributes['product']))
            $ids = array_keys($attributes['product']);
        else
            $ids = [];
        foreach ($products as $product) {
            $collection_array = $product->collection_ids;
            $id = $collection->id;
            if (is_array($collection_array)) {
                if (in_array($product->id, $ids)) {
                    if (!in_array($id, $collection_array))
                        array_push($collection_array, $id);
                } else {
                    $key = array_search($id, $collection_array, true);
                    if(!is_bool($key))
                        unset($collection_array[$key]);
                }
            } else {
                $collection_array = [];
                array_push($collection_array, $id);
            }

            $product->update(['collection_ids' => array_values($collection_array)]);
        }

      return $collection->update($attributes);
    }

    public function getCollection($id)
    {
        return Collection::find($id);

    }
}
