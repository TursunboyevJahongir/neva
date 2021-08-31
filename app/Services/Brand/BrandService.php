<?php

namespace App\Services\Brand;

use App\Models\Brand;
use App\Models\Image;

class BrandService
{
    /**
     * @var Image
     */
    private $image;

    public function __construct()
    {
        $this->image = new Image();
    }

    public function all()
    {
        return Brand::with('image')
            ->latest('id')
            ->get();
    }

    public function create(array $attributes)
    {
        $brand = Brand::create($attributes);

        $file = $this->image->uploadFile($attributes['image'], 'brands');

        $brand->image()->create([
            'url' => '/'.$file
        ]);

        return $brand;
    }

    public function update(array $attributes, Brand $brand)
    {
        $brand->update($attributes);

        if (array_key_exists('image', $attributes)) {
            if ($brand->image()->exists()) {
                $brand->image->removeFile();
                $brand->image()->delete();
            }

            $file = $this->image->uploadFile($attributes['image'], 'brands');

            $brand->image()->create([
                'url' => '/'.$file
            ]);
        }

        return $brand;
    }

    public function delete(Brand $brand)
    {
        return $brand->delete();
    }
}
