<?php

namespace App\Services\Banner;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Image;
use App\Models\News;
use App\Models\Product;

class BannerService
{
    public function all()
    {
        return Banner::with('image')
            ->latest('id')
            ->paginate(config('app.per_page'));
    }

    public function object($model, $id)
    {
        $object = null;
        switch ($model) {
            case 'news':
                $object = News::find($id)->with('image')
                    ->latest('id')
                    ->paginate();
                break;
            case 'product':
                $object = Product::find($id)->with('image')
                    ->latest('id')
                    ->paginate();
                break;
            case 'category':
                $category_ids = Category::getDescendants($id);
                Product::whereIn('category_id', $category_ids)->paginate(10);
                break;
        }
        return $object;
    }

    public function create(array $attributes)
    {
        $banner = Banner::create($attributes);

        $file = Image::uploadFile($attributes['image'], 'banners');

        $banner->image()->create([
            'url' => $file
        ]);

        return $banner;
    }

    public function update(array $attributes, Banner $banner)
    {
        $banner->update($attributes);

        if (array_key_exists('image', $attributes)) {
            if ($banner->image()->exists()) {
                $banner->image->removeFile();
                $banner->image()->delete();
            }

            $file = Image::uploadFile($attributes['image'], 'banners');

            $banner->image()->create([
                'url' => $file
            ]);
        }

        return $banner;
    }

    public function delete(Banner $banner)
    {
        return $banner->delete();
    }
}
