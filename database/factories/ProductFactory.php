<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariation;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Generator;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_id' => Shop::all()->random()->id,
            'category_id' => Category::all()->random()->id,
            'name' => [
                'uz' => $this->faker->word,
                'ru' => $this->faker->word,
                'en' => $this->faker->word
            ],
            'content' => [
                'uz' => $this->faker->text(200),
                'ru' => $this->faker->text(200),
                'en' => $this->faker->text(200),
            ],
            'sku' => $this->faker->slug(),
            'slug' => $this->faker->slug(),
            'product_attribute_ids' => $this->faker->boolean ? [ProductAttributeValue::all()->random()->id] : [ProductAttributeValue::all()->random()->id, ProductAttributeValue::all()->random()->id],
            'quantity' => $this->faker->numberBetween(100, 150),
            'min_old_price' => $this->faker->boolean ? $this->faker->numberBetween(1000, 10000) : null,
            'min_price' => $this->faker->numberBetween(10000, 500000),
            'max_price' => $this->faker->numberBetween(1000, 1000000),
            'position' => $this->faker->numberBetween(0, 150),
            'max_percent' => $this->faker->boolean ? $this->faker->numberBetween(5, 50) : null,
            'tag' => $this->faker->randomElement(['sport,tennis', 'sport,basketball', 'boy', 'boy,girl', 'girl', 'mather', 'father', 'family', 'book', 'book,pencil', 'book,pen']),
        ];
    }
}
