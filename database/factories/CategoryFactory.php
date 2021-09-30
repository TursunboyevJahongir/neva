<?php

namespace Database\Factories;

use App\Models\Category;
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
