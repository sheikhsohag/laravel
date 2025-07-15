<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ProductService{
    public function storeFile($file){
      $extention = $file->getClientOriginalExtension();
      $filePath = now()->format('ymd-His') . '_' . uniqid() . '.' . $extention;

      $location = Storage::disk('public')->putFileAs('/products', $file, $filePath);
      return $location;
    }
}