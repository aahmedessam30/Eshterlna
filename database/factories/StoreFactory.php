<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name_ar'    => $this->faker->name,
            'name_en'    => $this->faker->name,
            'phone'      => $this->faker->phoneNumber,
            'email'      => $this->faker->email,
            'address_ar' => $this->faker->address,
            'address_en' => $this->faker->address,
            'lat'        => $this->faker->latitude,
            'lng'        => $this->faker->longitude,
            'online'     => $this->faker->boolean,
            'city_id'    => City::all()->random(),
            'user_id'    => User::merchant()->get()->random()->id,
        ];
    }
}
