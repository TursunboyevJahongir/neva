<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class ShopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shop::create([
            'user_id' => 1,
            'name' => 'Diva_Kids',
            'address' => "Toshkent ",
            'slug' => Str::slug('DivaKids'),
            'delivery_price' => 15000,
            'work_day' => [
                'Воскресенье',
                'Понедельник',
                'Вторник',
                'Среда',
                'Четверг',
                'Пятница',
                'Суббота'
            ],
            'active' => 1,
        ]);


    }
}
