<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;
use App\Models\Image;

class BannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Banner::create([
            'name' => [
                'ru' => 'Купон (информативный баннер)',
                'uz' => ''
            ],
            'description' => [
                'ru' => 'Купон (информативный баннер)',
                'uz' => ''
            ],
            'link' => 'test',
            'object_id'=>1,
            'object'=>'category'
        ])->image()->create([
            'url' => '/images/bg-01.jpg'
        ]);

        Banner::create([
            'name' => [
                'ru' => 'Хаггис',
                'uz' => ''
            ],
            'description' => [
                'ru' => 'Хаггис',
                'uz' => ''
            ],
            'link' => 'test',
            'object_id'=>1,
            'object'=>'shop'
        ])->image()->create([
            'url' => '/images/bg-01.jpg'
        ]);

        Banner::create([
            'name' => [
                'ru' => 'Правильное питание',
                'uz' => ''
            ],
            'description' => [
                'ru' => 'Правильное питание',
                'uz' => ''
            ],
            'link' => 'test',
            'object_id'=>2,
            'object'=>'category'
        ])->image()->create([
            'url' => '/images/bg-01.jpg'
        ]);


    }
}
