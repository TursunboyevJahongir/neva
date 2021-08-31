<?php

namespace App\Services\Banner;

use App\Models\Banner;
use App\Models\Image;

class BannerService
{
    public function all()
    {
        return Banner::with('image')
            ->latest('id')
            ->paginate(config('app.per_page'));
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
