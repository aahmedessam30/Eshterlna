<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use App\Models\Vat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
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
            'pay_price'      => $this->faker->randomFloat(2, 0, 100),
            'sale_price'     => $this->faker->randomFloat(2, 0, 100),
            'category_id'    => $this->faker->numberBetween(1, 10),
            'lowest_price'   => $this->faker->randomFloat(2, 0, 100),
            'discount'       => $this->faker->randomFloat(2, 0, 100),
            'code'           => $this->faker->randomNumber(9),
            'online'         => $this->faker->boolean,
            'brand_id'       => Brand::all()->random()->id,
            'category_id'    => Category::all()->random()->id,
            'user_id'        => User::all()->random()->id,
            'vat_id'         => Vat::all()->random()->id,
            'vat_state'      => $this->faker->randomElement(['0', '1' ,'2']),
        ];
    }
}
