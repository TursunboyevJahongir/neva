<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Interest;
use Illuminate\Database\Eloquent\Factories\Factory;

class interestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Interest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => [
                'uz' => $this->faker->unique()->word,
                'ru' => $this->faker->unique()->word,
                'en' => $this->faker->unique()->word
            ],
            'description' => [
                'uz' => $this->faker->text(150),
                'ru' => $this->faker->text(150),
                'en' => $this->faker->text(150),
            ],

        ];
    }
}
