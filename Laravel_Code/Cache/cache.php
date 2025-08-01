Action	Code
Clear single key	Cache::forget('key')
Clear all cache (dangerous)	Cache::flush()
❌ Invalid	Cache::clear('key')



$products = Cache::remember('products_all', now()->addMinutes(10), function () {
    Log::info('🔁 Product query executed from DB');
    return Product::all();
});
