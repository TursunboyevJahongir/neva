<?php

namespace App\Observers\Brand;

use App\Models\Brand;
use Illuminate\Support\Str;

class BrandObserver
{
    public function creating(Brand $brand)
    {
        $brand->slug = Str::slug($brand->name);
    }

    public function updating(Brand $brand)
    {
        $brand->slug = Str::slug($brand->name);
    }

    public function deleting(Brand $brand)
    {
        if ($brand->image()->exists()) {
            $brand->image->removeFile();
            $brand->image()->delete();
        }
    }
}
