<?php

namespace Database\Seeders;

use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Database\Seeder;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductVariation;

class ProductAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       ProductAttribute::create([
            'name' => [
                'ru' => 'Цвет',
                'uz' => 'Цвет'
            ],
        ]);
       ProductAttribute::create([
            'name' => [
                'ru' => 'Размер',
                'uz' => 'Размер'
            ],
        ]);
       ProductAttribute::create([
            'name' => [
                'ru' => 'Серия',
                'uz' => 'Серия'
            ],
        ]);

        ProductAttributeValue::create([
            'name' => [
                'ru' => 'Красный',
                'uz' => 'Красный'
            ],
            'product_attribute_id'=>1
        ]);
        ProductAttributeValue::create([
            'name' => [
                'ru' => 'Синий',
                'uz' => 'Синий'
            ],
            'product_attribute_id'=>1
        ]);

        ProductAttributeValue::create([
            'name' => [
                'ru' => '120x60',
                'uz' => '120x60'
            ],
            'product_attribute_id'=>2
        ]);
    }
}
