<?php

namespace App\Observers\Product;

use App\Models\ProductVariation;
use App\Models\Image;
use App\Services\Product\ProductService;
use Illuminate\Support\Str;

class ProductVariationObserver
{

    public  $service;

    public function __construct(ProductService $service) {
        $this->service = $service;
    }

    public function deleting(ProductVariation $variation)
    {
        // delete image
        if ($variation->image()->exists()) {
            $variation->image->removeFile();
            $variation->image()->delete();
        }
    }
}
