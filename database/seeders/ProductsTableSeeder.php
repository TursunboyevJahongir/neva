<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductVariation;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Product::create([
            'name' => [
                'ru' => 'Наволочка 2119',
                'uz' => 'Наволочка 2119'
            ],
            'content' => [
                'ru' => 'Наволочка декоративная на молнии 45х45 см. Современная прочная ткань наволочки Оксфорд - приятна на ощупь, не дает усадки, не линяет, не выгорает на солнце, благодаря чему декоративная подушка долгое время сохраняет свой первоначальный вид. 
С наволочками от Willmoda в оригинальных ярких расцветках Вы можете обновить обстановку, создать праздничную атмосферу либо расставить яркие акценты. Стильная декоративная наволочка  отлично впишется в интерьер и подчеркнет вкус обладателя.

Оригинальный подарок вашим друзам и близким.

Изделие следует стирать при температуре не выше 40 градусов, применять мягкие моющие средства. Оттенок может незначительно отличаться от представленного на фото.',
                'uz' => ''
            ],
            'shop_id' => 1,
            'slug' => '401021194545',
            'category_id' => 1,
            'min_price' => 15000,
            'max_price' => 15000
        ]);
         ProductVariation::create([
             'product_id'=>1,
             'product_attribute_value_ids'=>[1,2],
             'quantity'=>14,
             'price'=>15000,
             'old_price'=>15000
         ]);

    }
}
