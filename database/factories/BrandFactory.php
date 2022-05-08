<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name_ar'        => $this->faker->word,
            'name_en'        => $this->faker->word,
            'description_ar' => $this->faker->sentence,
            'description_en' => $this->faker->sentence,
            'online'         => $this->faker->boolean,
            'code'           => $this->faker->randomNumber(9),
            'user_id'        => User::all()->random()->id,
        ];
    }
}
