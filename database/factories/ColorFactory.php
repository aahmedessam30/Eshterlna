<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Color>
 */
class ColorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name_ar'   => $this->faker->word,
            'name_en'   => $this->faker->word,
            'colorHash' => $this->faker->hexColor,
            'online'    => $this->faker->boolean,
            'user_id' => User::merchant()->get()->random()->id,
        ];
    }
}
