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
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ShopsTableSeeder::class);
        $this->call(Categories::class);
        $this->call(BannersTableSeeder::class);
        $this->call(ProductAttributesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
    }
}
