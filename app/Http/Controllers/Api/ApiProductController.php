<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Interfaces\ApiProductRepositoryInterface;

class ApiProductController extends Controller
{
    protected $repository;

    public function __construct(ApiProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $products = $this->repository->getAllProducts();

        return response()->json([
            'products' => ProductResource::collection($products),
        ]);
    }
}
