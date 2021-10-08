<?php

namespace Database\Factories;

use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * @throws Exception
     */
    public function configure()
    {
        $fake = $this->faker;
        return $this->afterCreating(static function (Category $intro) use ($fake) {
            @mkdir(public_path('/uploads/category/'), 0777, true);
                $time = time() . random_int(1000, 60000);
                copy($fake->imageUrl(), public_path('/uploads/category/') . $time . '.jpg');
                $path = '/uploads/category/' . $time . '.jpg';
                $intro->ico()->create([
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
        return [
            'parent_id' => $this->faker->boolean ? null : Category::where('parent_id', '=', null)->get()->random()->id,
            'name' => [
                'uz' => $this->faker->unique()->word,
                'ru' => $this->faker->unique()->word,
                'en' => $this->faker->unique()->word
            ],
            'slug' => $this->faker->slug,
            'position' => $this->faker->numberBetween(0, 150)
        ];
    }
}
