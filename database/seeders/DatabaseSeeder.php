<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use App\Models\Color;
use App\Models\Setting;
use App\Models\Size;
use App\Models\Store;
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
    }
}
