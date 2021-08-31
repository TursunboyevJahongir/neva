<?php

namespace App\Observers\Product;

use App\Models\Product;
use App\Models\Image;
use App\Services\Product\ProductService;
use Illuminate\Support\Str;

class ProductObserver
{

    private $service;

    public function __construct(ProductService $service) {
        $this->service = $service;
    }

    public function creating(Product $product)
    {
        $product->slug = Str::slug($product->name . '-' . $product->id);
    }

    public function updating(Product $product)
    {
        $product->slug = Str::slug($product->name . '-' . $product->id);
    }

    public function deleting(Product $product)
    {
        // delete cover
        if ($product->image()->exists()) {
            $product->image->removeFile();
            $product->image()->delete();
        }
        // delete other photos
        foreach ($product->images as $image) {
            $image->removeFile();
            Image::destroy($image->id);
        }
    }
}
