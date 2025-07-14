<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BasicEagerLoading extends Controller
{
    public function index()
    {
        // Basic eager loading to prevent N+1 queries
        $users = User::with('posts', 'comments')->get();
        
        return response()->json($users);
    }
}