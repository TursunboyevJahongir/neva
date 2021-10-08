<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariation;
use App\Models\Shop;
use Exception;
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
     * @throws Exception
     */
    public function configure()
    {
        $faker = $this->faker;
        return $this->afterCreating(static function (ProductVariation $product) use ($faker) {
            @mkdir(public_path('/uploads/product/'), 0777, true);
            $time = time() . random_int(1000, 60000);
            copy($faker->imageUrl(), public_path('/uploads/product/') . $time . '.jpg');
            $path = '/uploads/product/' . $time . '.jpg';
            $product->image()->create([
                'url' => $path,
            ]);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $attribute = ProductAttribute::all()->random()->id;
        $attribute2 = ProductAttribute::all()->random()->id;
        return [
            'product_id' => Product::all()->random()->id,
//            'product_attribute_value_ids' => $this->faker->boolean ? [ProductAttributeValue::all()->random()->id] : [ProductAttributeValue::all()->random()->id, ProductAttributeValue::all()->random()->id],
            'product_attributes' => $this->faker->boolean ?
                [[

                    'attribute' => $attribute,
                    "values" =>
                        [
                            ProductAttributeValue::where('product_attribute_id', $attribute)->get()->random()->id,
                            ProductAttributeValue::where('product_attribute_id', $attribute)->get()->random()->id,
                        ]
                ]] :
                [
                    [
                        'attribute' => $attribute,
                        "values" =>
                            [
                                ProductAttributeValue::where('product_attribute_id', $attribute)->get()->random()->id,
                                ProductAttributeValue::where('product_attribute_id', $attribute)->get()->random()->id,
                            ]
                    ],
                    [
                        'attribute' => $attribute2,
                        "values" =>
                            [
                                ProductAttributeValue::where('product_attribute_id', $attribute2)->get()->random()->id,
                                ProductAttributeValue::where('product_attribute_id', $attribute2)->get()->random()->id,
                            ]
                    ]
                ],
            'quantity' => $this->faker->numberBetween(100, 150),
            'old_price' => $this->faker->numberBetween(10000, 500000),
            'price' => $this->faker->numberBetween(400000, 10000000),];
    }
}
