<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Intro::factory(1)->create();

        $this->call(AdminSeeder::class);
        $this->call(PermissionsSeeder::class);


        $this->call(ShopsTableSeeder::class);
        $this->call(CouponSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(Categories::class);
        $this->call(BannersTableSeeder::class);
        \App\Models\Category::factory(10)->create();
        \App\Models\Category::factory(10)->create();
        \App\Models\Category::factory(10)->create();
        $this->call(ProductAttributesTableSeeder::class);
        \App\Models\Interest::factory(10)->create();
        \App\Models\Product::factory(10)->create();
        \App\Models\ProductVariation::factory(10)->create();

    }
}
