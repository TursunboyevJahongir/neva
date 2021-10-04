<?php

namespace App\Services\Favorite;


use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
    public function all($size = 10): LengthAwarePaginator
    {
        $favoriteIds = Favorite::query()
            ->where('user_id', Auth::id())
            ->pluck('id');
        return Product::query()->whereIn('id', $favoriteIds)->paginate($size);
    }

    //WishList
    public function add(array $product)
    {
        $array = ['user_id' => Auth::id(), 'product_id' => $product['product_id']];
        $model = Favorite::query()->where($array);
        if (!is_null($model->first())) {
            $model->delete();
        } else
            Favorite::create($array);
        return true;
    }

}
