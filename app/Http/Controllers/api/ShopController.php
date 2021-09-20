<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{

    public function show(Shop $id)
    {
        return $this->success($id->load('images:url,imageable_id'));
    }

}
