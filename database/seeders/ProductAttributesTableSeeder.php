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
                'uz' => 'Цвет',
                'en' => 'Цвет'
            ],
        ]);
        ProductAttribute::create([
            'name' => [
                'ru' => 'Размер',
                'uz' => 'Размер',
                'en' => 'Размер',
            ],
        ]);
        ProductAttribute::create([
            'name' => [
                'ru' => 'Серия',
                'uz' => 'Серия',
                'en' => 'Серия',
            ],
        ]);

        ProductAttributeValue::create([
            'name' => [
                'ru' => 'Красный',
                'uz' => 'Qizil',
                'en' => 'Red',
            ],
            'product_attribute_id' => 1
        ]);
        ProductAttributeValue::create([
            'name' => [
                'ru' => 'Синий',
                'uz' => 'Moviy',
                'en' => 'Blue',
            ],
            'product_attribute_id' => 1
        ]);

        ProductAttributeValue::create([
            'name' => [
                'uz' => 'Oq',
                'ru' => 'Белый',
                'en' => 'White'
            ],
            'product_attribute_id' => 1
        ]);

        ProductAttributeValue::create([
            'name' => [
                'uz' => 'Qora',
                'ru' => 'Черный',
                'en' => 'Black'
            ],
            'product_attribute_id' => 1
        ]);

        ProductAttributeValue::create([
            'name' => [
                'uz' => 'Yashil',
                'ru' => 'Зеленый',
                'en' => 'Green'
            ],
            'product_attribute_id' => 1
        ]);

        ProductAttributeValue::create([
            'name' => [
                'uz' => 'Qaymoq',
                'ru' => 'Молочный',
                'en' => 'Cream color'
            ],
            'product_attribute_id' => 1
        ]);

        ProductAttributeValue::create([
            'name' => [
                'ru' => '120x60',
                'uz' => '120x60',
                'en' => '120x60',
            ],
            'product_attribute_id' => 2
        ]);
        ProductAttributeValue::create([
            'name' => [
                'ru' => '720x480',
                'uz' => '720x480',
                'en' => '720x480',
            ],
            'product_attribute_id' => 2
        ]);
        ProductAttributeValue::create([
            'name' => [
                'ru' => 'XL',
                'uz' => 'XL',
                'en' => 'XL',
            ],
            'product_attribute_id' => 2
        ]);
        ProductAttributeValue::create([
            'name' => [
                'ru' => 'X',
                'uz' => 'X',
                'en' => 'X',
            ],
            'product_attribute_id' => 2
        ]);
        ProductAttributeValue::create([
            'name' => [
                'ru' => 'X',
                'uz' => 'X',
                'en' => 'X',
            ],
            'product_attribute_id' => 2
        ]);
        ProductAttributeValue::create([
            'name' => [
                'ru' => '16135153153',
                'uz' => '16135153153',
                'en' => '16135153153',
            ],
            'product_attribute_id' => 3
        ]);
    }
}
