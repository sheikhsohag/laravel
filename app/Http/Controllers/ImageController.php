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
            'file'=>'required|file|mimes:pdf,png,jpeg,jpg|max:2048'
        ]);

        $file = $request->file('file');
        $fileExtension = $file->getClientOriginalExtension();

        $filePath = now()->format('ymd-him') . '.' . $fileExtension;
        $path = Storage::disk('public')->putFileAs('images/uploads', $file, $filePath);
        $response = image::create([
            'file'=>$path
        ]);

        return response()->json([
            'success'=>true,
            "message"=>"successfull",
            "data"=>$response
        ]);
    }
}