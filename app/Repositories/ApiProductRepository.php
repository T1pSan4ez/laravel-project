<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Collection;

class ApiProductRepository implements ApiProductRepositoryInterface
{
    public function getAllProducts(): Collection
    {
        return Product::all();
    }
}
