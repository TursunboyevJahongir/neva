<?php

namespace App\Services\Favorite;


use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
    public function all($orderBy = 'created_at', $sort = 'DESC', $size = 10)
    {
        $favorite = Favorite::query()
            ->where('user_id', Auth::id())
            ->orderBy($orderBy, $sort);
        return $favorite->paginate($size);
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