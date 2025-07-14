<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class NestedEagerLoading extends Controller
{
    public function index()
    {
        // Load multiple levels of relationships
        $users = User::with('posts.comments.author')->get();
        
        return response()->json($users);
    }
}