<?php

namespace Database\Seeders;


use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Categories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => [
                'ru' => 'Питание',
                'uz' => ''
            ],
            'slug' => Str::slug('Питание'),
        ])->ico()->create([
            'url' => '/images/bg-01.jpg'
        ]);
        Category::create([
            'name' => [
                'ru' => 'Игрушки и игры',
                'uz' => ''
            ],
            'slug' => Str::slug('Игрушки и игры'),
        ])->ico()->create([
            'url' => '/images/bg-01.jpg'
        ]);

        Category::create([
            'name' => [
                'ru' => 'Гигиена и уход',
                'uz' => ''
            ],
            'slug' => Str::slug('Гигиена и уход'),
        ])->ico()->create([
            'url' => '/images/bg-01.jpg'
        ]);

    }
}
