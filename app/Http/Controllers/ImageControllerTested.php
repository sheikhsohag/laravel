<?php

namespace App\Http\Controllers;

use App\Models\Image; // Changed to capitalized model name
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function show($id) 
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Get the file path from storage
        $filePath = $image->file;
        
        // Check if file exists
        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Get the full path to the file
        $fullPath = Storage::disk('public')->path($filePath);
        
        // Get the original filename (if stored) or extract from path
        $originalName = $image->original_name ?? basename($filePath);
        
        // Return the file as download
        return Storage::disk('public')->download($filePath, $originalName);
    }
        
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf|max:2048'
        ]);

        $file = $request->file('file');
        $fileExtension = $file->getClientOriginalExtension();
        
        // Generate more unique filename
        $filePath = 'uploads/' . now()->format('ymd_His') . '_' . uniqid() . '.' . $fileExtension;
        
        // Store the file
        $path = Storage::disk('public')->putFileAs('uploads', $file, $filePath);
        
        $fileop = Image::create([
            'file' => $path,
            'original_name' => $file->getClientOriginalName() // Optional: store original name
        ]);

        return response()->json([
            "success" => true,
            "message" => "successful",
            "data" => [
                "path" => $path,
                "url" => Storage::disk('public')->url($path),
                "id" => $fileop->id
            ]
        ]);
    }
}