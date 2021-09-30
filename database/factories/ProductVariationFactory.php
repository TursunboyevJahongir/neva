<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariation;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductVariation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::all()->random()->id,
            'product_attribute_value_ids' => $this->faker->boolean ? [ProductAttributeValue::all()->random()->id] : [ProductAttributeValue::all()->random()->id, ProductAttributeValue::all()->random()->id],
            'quantity' => $this->faker->numberBetween(100, 150),
            'old_price' => $this->faker->numberBetween(10000, 500000),
            'price' => $this->faker->numberBetween(400000, 10000000),
            'percent' => $this->faker->boolean ? $this->faker->numberBetween(5, 50) : null,
        ];
    }
}
