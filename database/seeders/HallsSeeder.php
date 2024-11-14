<?php

namespace Database\Seeders;

use App\Models\Cinema;
use App\Models\Hall;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class HallsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $cinemas = Cinema::all();

        foreach ($cinemas as $cinema) {
            for ($i = 1; $i <= rand(1, 3); $i++) {
                $hallName = $i === 3 ? 'Hall ' . $i . ' VIP' : 'Hall ' . $i;

                Hall::create([
                    'cinema_id' => $cinema->id,
                    'name' => $hallName,
                ]);
            }
        }
    }
}
