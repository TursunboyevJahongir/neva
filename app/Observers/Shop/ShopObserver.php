<?php

namespace App\Observers\Shop;

use App\Models\Shop;
use Illuminate\Support\Str;

class ShopObserver
{
    public function creating(Shop $shop)
    {
        $shop->slug = Str::slug($shop->name);
    }

    public function updating(Shop $shop)
    {
        $shop->slug = Str::slug($shop->name);
    }

    public function deleting(Shop $shop)
    {
        if ($shop->image()->exists()) {
            $shop->image->removeFile();
            $shop->image()->delete();
        }
    }
}
