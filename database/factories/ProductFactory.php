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
     * @throws Exception
     */
    public function configure()
    {
        $faker = $this->faker;
        return $this->afterCreating(static function (Product $product) use ($faker) {

//            $name = $fake->image(public_path('uploads/'),640,480, null, false);
//            $path = '/uploads/'.$name;
            @mkdir(public_path('/uploads/product/'), 0777, true);
            $size = random_int(3, 7);
            for ($i = 0; $i <= $size; $i++) {
                $time = time() . random_int(1000, 60000);
                copy($faker->imageUrl(), public_path('/uploads/product/') . $time . '.jpg');
                $path = '/uploads/product/' . $time . '.jpg';
                $product->images()->create([
                    'url' => $path,
                ]);
            }

            $time = time() . random_int(1000, 60000);
            copy($faker->imageUrl(), public_path('/uploads/product/') . $time . '.jpg');
            $path = '/uploads/product/' . $time . '.jpg';
            $product->image()->create([
                'url' => $path,
                'cover_image' => 1,
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
        return [
            'shop_id' => Shop::all()->random()->id,
            'category_id' => Category::all()->random()->id,
            'name' => [
                'uz' => $this->faker->word,
                'ru' => $this->faker->word,
                'en' => $this->faker->word
            ],
            'description' => [
                'uz' => $this->faker->text(200),
                'ru' => $this->faker->text(200),
                'en' => $this->faker->text(200),
            ],
            'sku' => $this->faker->slug(),
            'slug' => $this->faker->slug(),
//            'product_attributes' => $this->faker->boolean ?
//                [[
//                    'attribute' => ProductAttribute::all()->random()->id,
//                    "values" =>
//                        [
//                            ProductAttributeValue::all()->random()->id,
//                            ProductAttributeValue::all()->random()->id,
//                        ]
//                ]] :
//                [
//                    [
//                        'attribute' => ProductAttribute::all()->random()->id,
//                        "values" =>
//                            [
//                                ProductAttributeValue::all()->random()->id,
//                                ProductAttributeValue::all()->random()->id,
//                            ],
//                    ],
//                    [
//                        'attribute' => ProductAttribute::all()->random()->id,
//                        "values" =>
//                            [
//                                ProductAttributeValue::all()->random()->id,
//                                ProductAttributeValue::all()->random()->id,
//                            ]
//                    ]
//                ],
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
