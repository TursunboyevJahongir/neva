<?php

namespace App\Services\Brand;

use App\Models\Shop;

class BrandService
{

    public function all()
    {
        return Shop::active()->where('is_brand', '=', true)->get();
    }


}
