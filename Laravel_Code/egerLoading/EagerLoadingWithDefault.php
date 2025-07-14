<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class EagerLoadingWithDefault extends Controller
{
    public function index()
    {
        // Provide default model if relationship is null
        $posts = Post::with(['author' => function($query) {
            $query->withDefault([
                'name' => 'Guest Author'
            ]);
        }])->get();
        
        return response()->json($posts);
    }
}