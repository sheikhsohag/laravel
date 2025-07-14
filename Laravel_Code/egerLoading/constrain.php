<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ConstrainedEagerLoading extends Controller
{
    public function index()
    {
        // Eager load with conditions on relationships
        $users = User::with(['posts' => function($query) {
            $query->where('active', 1)->orderBy('created_at', 'desc');
        }])->get();
        
        return response()->json($users);
    }
}