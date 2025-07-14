<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DynamicEagerLoading extends Controller
{
    public function index()
    {
        $relations = ['profile'];
        
        // Dynamically add relationships based on conditions
        if (request('with_posts')) {
            $relations[] = 'posts';
        }
        
        $users = User::with($relations)->get();
        
        return response()->json($users);
    }
}