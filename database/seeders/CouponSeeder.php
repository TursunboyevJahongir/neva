<?php

namespace Database\Seeders;

use App\Enums\SaleTypeEnum;
use App\Models\Coupon;
use App\Models\Shop;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coupon::create([
            'creator_id' => 1,
            'object_id' => null,
            'coupon_type' => null,
            'code' => "first_coupon",
            'start_at' => null,
            'end_at' => null,
            'description' => "First Coupon",
            'sale_type' => SaleTypeEnum::PERCENT,
            'value' => 5,
            'count' => null,
            'price' => null,
            'active' => 1,
        ]);


    }
}
