<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Interest;
use App\Models\Intro;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class IntroFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Intro::class;

    /**
     * @throws Exception
     */
    public function configure(): IntroFactory
    {
        $fake = $this->faker;
        return $this->afterCreating(static function (Intro $intro) use ($fake) {

//            $name = $fake->image(public_path('uploads/'),640,480, null, false);
//            $path = '/uploads/'.$name;
            @mkdir(public_path('/uploads/'), 0777, true);
            $size = random_int(3, 7);
            for ($i = 0; $i <= $size; $i++) {
                $time = time() . random_int(1000, 60000);
                copy($fake->imageUrl(), public_path('/uploads/') . $time . '.jpg');
                $path = '/uploads/' . $time . '.jpg';
                $intro->images()->create([
                    'url' => $path,
                ]);
            }
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
            'title' => [
                'uz' => $this->faker->word,
                'ru' => $this->faker->word,
                'en' => $this->faker->word
            ],
            'description' => [
                'uz' => $this->faker->text(150),
                'ru' => $this->faker->text(150),
                'en' => $this->faker->text(150),
            ],

        ];
    }
}
