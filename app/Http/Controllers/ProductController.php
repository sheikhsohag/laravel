<?php

namespace App\Http\Controllers;

use App\Events\ProductCreate;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    private $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function store(ProductRequest $request){
        $data = $request->validated();
        if($request->hasFile('image'))
        {
            $path = $this->productService->storeFile($data['image']);
        }

        $product = Product::create($data);
        
        $product->image = $path;
        $product->save();
        ProductCreate::dispatch($product);
        return response()->json($product);
    }
}