<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EagerLoadingSpecificColumns extends Controller
{
    public function index()
    {
        // Select only specific columns from relationships
        $users = User::with(['posts:id,user_id,title', 'profile:id,user_id,bio'])->get();
        
        return response()->json($users);
    }
}