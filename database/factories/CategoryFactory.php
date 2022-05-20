<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
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
            'online'         => $this->faker->randomElement([0, 1]),
            'category_id'    => $this->faker->numberBetween(0, 20),
            'user_id' => User::merchant()->get()->random()->id,
        ];
    }
}
