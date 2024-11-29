<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ApiProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'products' => ProductResource::collection($products),
        ]);
    }
}
