$product = Product::find(1);

// Replicate (copy) the product
$newProduct = $product->replicate();

// Modify fields if needed
$newProduct->title = 'Copy of ' . $product->title;

// Save the new product
$newProduct->save();
