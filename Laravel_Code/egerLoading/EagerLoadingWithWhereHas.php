<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EagerLoadingWithWhereHas extends Controller
{
    public function index()
    {
        // Only load users with specific related models
        $users = User::whereHas('posts', function($query) {
            $query->where('views', '>', 1000);
        })->with(['posts' => function($query) {
            $query->where('views', '>', 1000);
        }])->get();
        
        return response()->json($users);
    }
}