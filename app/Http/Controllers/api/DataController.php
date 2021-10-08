<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;

use App\Http\Resources\Api\v1\IntroResource;
use App\Models\Intro;
use App\Models\Shop;
use Illuminate\Http\Request;

class DataController extends ApiController
{

    public function intro()
    {
        $intro = Intro::select()->first();
        return $this->success(__('success'),new IntroResource($intro));
    }

}
