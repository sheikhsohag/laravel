<?php

namespace App\Http\Controllers;

use App\Events\ProductCreate;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    private $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = Cache::remember('products_all', now()->addMinutes(10), function () {
            Log::info('🔁 Product query executed from DB');
            return Product::all();
        });
        // Cache::forget('products_all');

        return response()->json($products);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $path = $this->productService->storeFile($data['image']);
        }

        $product = Product::create($data);

        $product->image = $path;
        $product->save();
        ProductCreate::dispatch($product);
        return response()->json($product);
    }
}
