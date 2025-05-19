<?php

namespace App\Http\Controllers;

use App\Models\image;
use HelperFunction;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
class ImageController extends Controller
{
    public function show($id){
        $file = image::find($id);
        if(!$file)
        return response()->json([

            "success"=>false,
            "message"=>"not found",
            "data"=>null
        ]);

        if(Storage::disk('public')->exists($file->file))
        {
            return response()->json("yes exist");
        }
        
        return response()->json([
            "success"=>true,
            "message"=>"found",
            "data"=>$file
        ]);


    }

    public function store(Request $request){
        $request->validate([
            'files' => 'required|array',
            'files.*'=>'required|file|mimes:pdf,png,jpeg,jpg|max:2048'
        ]);

        $files = $request->file('files');

        $responses = [];

        foreach($files as $file)
        {
            $path = $this->storeImage($file);
            $responses[] = image::create([
                'file'=>$path
                ]);
        }
       
        return response()->json([
            'success'=>true,
            "message"=>"successfull",
            "data"=>$responses
        ]);
    }


    public function storeImage($file)
    {
         $fileExtension = $file->getClientOriginalExtension();

        $filePath = now()->format('ymd-him') . '.' . $fileExtension;
        $path = Storage::disk('public')->putFileAs('images/uploads', $file, $filePath);
        return $path;
    }

    public function deleteImage($filename)
    {
        $path = 'uploads/' . $filename;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Image not found.'
        ], 404);
    }

    function getAllUploadedFiles($asUrl = false)
    {
        $files = Storage::disk('public')->files('uploads');

        if ($asUrl) {
            return array_map(fn($file) => Storage::url($file), $files);
        }

        return $files; // e.g., ['uploads/pic1.jpg', ...]
    }

}