<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Small Popcorn (Caramel)', 'price' => 150.00, 'image' => 'images/popcorn_small.png'],
            ['name' => 'Medium Popcorn (Caramel)', 'price' => 200.00, 'image' => 'images/popcorn_medium.png'],
            ['name' => 'Large Popcorn (Caramel)', 'price' => 250.00, 'image' => 'images/popcorn_large.png'],
            ['name' => 'Small Popcorn (Cheese)', 'price' => 100.00, 'image' => 'images/popcorn_small_cheese.png'],
            ['name' => 'Medium Popcorn (Cheese)', 'price' => 150.00, 'image' => 'images/popcorn_medium_cheese.png'],
            ['name' => 'Large Popcorn (Cheese)', 'price' => 200.00, 'image' => 'images/popcorn_large_cheese.png'],
            ['name' => 'Coca-Cola', 'price' => 50.00, 'image' => 'images/coca_cola.png'],
            ['name' => 'Fanta', 'price' => 50.00, 'image' => 'images/fanta.png'],
            ['name' => 'Orange Juice', 'price' => 60.00, 'image' => 'images/orange_juice.png'],
        ];

        DB::table('products')->insert($products);
    }
}
