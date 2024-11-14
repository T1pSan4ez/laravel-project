<?php

namespace Database\Seeders;

use App\Models\Cinema;
use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CinemasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $cities = City::all();

        foreach ($cities as $city) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                Cinema::create([
                    'city_id' => $city->id,
                    'name' => $faker->company . ' Cinema',
                    'address' => $faker->address,
                ]);
            }
        }
    }
}
