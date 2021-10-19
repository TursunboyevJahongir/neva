<?php

namespace Database\Seeders;

use App\Enums\SaleTypeEnum;
use App\Models\Coupon;
use App\Models\Shop;
use App\Models\UserCoupons;
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
            'description' =>
                [
                    'ru' => 'Купон на первую регистрацию',
                    'en' => "First registration coupon",
                    'uz' => "Birinchi ro'yxatdan o'tish kuponi"
                ],
            'sale_type' => SaleTypeEnum::PERCENT,
            'value' => 5,
            'count' => null,
            'price' => null,
            'active' => 1,
        ]);
        Coupon::create([
            'creator_id' => 1,
            'object_id' => 1,
            'coupon_type' => 'user',
            'code' => "user_coupon",
            'start_at' => null,
            'end_at' => null,
            'description' =>
                [
                    'ru' => 'Купон на user регистрацию',
                    'en' => "User registration coupon",
                    'uz' => "Ro'yxatdan o'tganga kupon"
                ],
            'sale_type' => SaleTypeEnum::PRICE,
            'value' => 50000,
            'count' => null,
            'price' => null,
            'active' => 1,
        ]);
UserCoupons::create([
    'user_id' => 1,
    'coupon_id' => 1,
]);
    }
}
