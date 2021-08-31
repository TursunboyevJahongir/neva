<?php

namespace App\Observers\Banner;

use App\Models\Banner;

class BannerObserver
{
    public function deleting(Banner $banner)
    {
        if ($banner->image()->exists()) {
            $banner->image->removeFile();
            $banner->image()->delete();
        }
    }
}
