<?php

namespace Database\Factories;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * @throws Exception
     */
    public function configure()
    {
        $faker = $this->faker;
        return $this->afterCreating(static function (User $User) use ($faker) {
            @mkdir(public_path('/uploads/avatar/'), 0777, true);
            $time = time() . random_int(1000, 60000);
            copy($faker->imageUrl(), public_path('/uploads/avatar/') . $time . '.jpg');
            $path = '/uploads/avatar/' . $time . '.jpg';
            $User->avatar()->create([
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
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }


}
