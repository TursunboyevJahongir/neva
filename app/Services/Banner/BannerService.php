<?php

namespace App\Services\Banner;

use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\v1\ProductResource;
use App\Http\Resources\Api\v1\ProductShowResource;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Image;
use App\Models\News;
use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Database\Eloquent\Collection;

class BannerService
{
    public function all()
    {
        return Banner::active()->get();
    }

    public function object($model, $id,&$data,$request)
    {
        $object =null;

        switch ($model) {
            case 'shop':

               // $object = new PaginationResourceCollection(,
             //       ProductResource::class);
                break;
            case 'product':
                $object = new ProductShowResource(Product::query()->findOrFail($id));
                (new ProductService)->historyView($id);
                break;
            case 'category':
                $category=Category::findOrFail($id);
                $data = (new ProductService)->categoryProducts($category, $request);
                $object= new PaginationResourceCollection($data['products'],
                    ProductResource::class);
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
