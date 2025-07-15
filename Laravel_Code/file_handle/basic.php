 public function store(ProductRequest $request){
        $data = $request->validated();
        if($request->hasFile('image'))
        {
            $path = $this->productService->storeFile($data['image']);
        }

        $product = Product::create($data);
        $product->image = $path;
        $product->save();
        return response()->json($product);
    }



class ProductService{
    public function storeFile($file){
      $extention = $file->getClientOriginalExtension();
      $filePath = now()->format('ymd-His') . '_' . uniqid() . '.' . $extention;

      $location = Storage::disk('public')->putFileAs('/products', $file, $filePath);
      // Store in public disk (storage/app/public/products)
      // $path = $file->storeAs('products', $fileName, 'public');
    
      return $location;
    }
}

<!-- multiple image -->

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $this->productService->storeFile($image);
            $imagePaths[] = $path;
        }
    }