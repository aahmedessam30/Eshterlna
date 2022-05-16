<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use App\Models\Color;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\Size;
use App\Models\Store;
use App\Models\Vat;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\PaymentMethod::factory(10)->create();
        \App\Models\ShippingMethod::factory(10)->create();
        \App\Models\Vat::factory(2)->create();
        \App\Models\Setting::factory(1)->create();
        \App\Models\Brand::factory(20)->create();
        \App\Models\Country::factory(20)->has(City::factory(10))->create();
        \App\Models\Category::factory(20)->create();
        \App\Models\Store::factory(10)
            ->hasAttached(\App\Models\Item::factory(10)
                ->has(Color::factory(3))
                ->has(Size::factory(3)),
                ['quantity' => rand(1, 100)],
            )->create();
        \App\Models\Order::factory(10)
            ->hasAttached(
                \App\Models\Item::factory(10),
                ['quantity' => rand(1, 100)
                ,'price'   => rand(1, 100)
                ,'discount' => rand(1, 100)
                ,'tax'      => rand(1, 100)
                ,'shipping' => rand(1, 100)
                ,'weight'   => rand(1, 100)]
            )->create();
    }
}
