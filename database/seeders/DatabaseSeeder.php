<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminSeeder::class);
        $this->call(PermissionsSeeder::class);


        $this->call(ShopsTableSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(Categories::class);
        $this->call(BannersTableSeeder::class);
        \App\Models\Category::factory(10)->create();
        \App\Models\Category::factory(10)->create();
        \App\Models\Category::factory(10)->create();
        $this->call(ProductAttributesTableSeeder::class);
//        $this->call(ProductsTableSeeder::class);
        \App\Models\Interest::factory(30)->create();
//        \App\Models\Product::factory(15)->create()->each(function ($currency) {
//            $currency->variations()->save(\App\Models\ProductVariation::factory(2)->make());
//        });
        \App\Models\Product::factory(50)->create();
        \App\Models\ProductVariation::factory(150)->create();

    }
}
