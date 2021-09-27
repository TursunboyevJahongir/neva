<?php

namespace Database\Seeders;

use App\Http\Controllers\dataController;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $check = new dataController();
        $check->openJson();
//        District::create([
//            'name_uz' => 'Toshkent',
//            'name_ru' => 'Ташкент',
//            'name_en' => 'Tashkent',
//            'parent_id' =>  null,
//            'code' => 15
//        ]);
    }
}
