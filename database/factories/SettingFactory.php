<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'currency_ar' => 'جنيه',
            'currency_en' => 'EGP',
            'about_ar'    => $this->faker->paragraph,
            'about_en'    => $this->faker->paragraph,
            'terms_ar'    => $this->faker->paragraph,
            'terms_en'    => $this->faker->paragraph,
            'theme'       => $this->faker->randomElement(['light', 'dark']),
            'user_id'     => 1,
        ];
    }
}
