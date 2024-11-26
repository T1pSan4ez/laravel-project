<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            'Action',
            'Animation',
            'Comedy',
            'Crime',
            'Drama',
            'Experimental',
            'Fantasy',
            'Historical',
            'Horror',
            'Romance',
            'Science Fiction',
            'Thriller',
            'Western',
            'Musical',
            'War',
        ];

        foreach ($genres as $genre) {
            DB::table('genres')->insert([
                'name' => $genre,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
