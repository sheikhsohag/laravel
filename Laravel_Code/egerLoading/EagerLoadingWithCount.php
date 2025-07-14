<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EagerLoadingWithCount extends Controller
{
    public function index()
    {
        // Count related models without loading them
        $users = User::withCount('posts')->get();
        
        return response()->json($users);
    }
}