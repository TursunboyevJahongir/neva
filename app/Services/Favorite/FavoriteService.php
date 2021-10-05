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
            ->orderBy('created_at', "DESC")
            ->pluck('product_id')
            ->toArray();

        $ids_ordered = implode(',', $favoriteIds);
        return Product::query()->whereIn('id', $favoriteIds)->orderByRaw("FIELD(id, $ids_ordered)")->paginate($size);
    }

    public function add($data)
    {
        $data['user_id'] = Auth::id();
        $model = Favorite::query()->where($data);
//        dd($model->delete());
        $model->exists() ? $model->delete() : Favorite::create($data);
    }
}
