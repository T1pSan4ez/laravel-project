<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $products = $this->productRepository->getAllPaginated(10);
        return view('admin.products', compact('products'));
    }

    public function store(StoreProductRequest $request)
    {
        $this->productRepository->create($request->validated());

        return redirect()->route('products')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.products-edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productRepository->update($product, $request->validated());

        return redirect()->route('products')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->productRepository->delete($product);

        return redirect()->route('products')->with('success', 'Product deleted successfully.');
    }
}
