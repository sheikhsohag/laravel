<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LazyEagerLoading extends Controller
{
    public function index()
    {
        $users = User::all();
        
        // Load relationships after parent is retrieved
        if (request('load_posts')) {
            $users->load('posts');
        }
        
        return response()->json($users);
    }
}