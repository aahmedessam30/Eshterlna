<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'            => User::all()->random()->id,
            'store_id'           => Store::all()->random()->id,
            'payment_method_id'  => PaymentMethod::all()->random()->id,
            'shipping_method_id' => ShippingMethod::all()->random()->id,
            'status'             => $this->faker->randomElement(['0','1','2','3','4','5']),
            'total_price'        => $this->faker->randomFloat(2, 0, 100),
            'total_discount'     => $this->faker->randomFloat(2, 0, 100),
            'total_tax'          => $this->faker->randomFloat(2, 0, 100),
            'total_shipping'     => $this->faker->randomFloat(2, 0, 100),
            'total_weight'       => $this->faker->randomFloat(2, 0, 100),
            'total_items'        => $this->faker->randomNumber(2),
            'ordered_at'         => now(),
            'shipped_at'         => now(),
            'cancelled_at'       => now(),
            'completed_at'       => now(),
        ];
    }
}
