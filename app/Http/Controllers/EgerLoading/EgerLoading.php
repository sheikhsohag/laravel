<?php

namespace App\Http\Controllers\EgerLoading;
use App\Http\Controllers\Controller;
use App\Models\User;

class EgerLoading extends Controller
{
    public function index(){
        $user = User::select('id','name', 'email')->with('category.products')->get();
    
        return response()->json($user);
    }
}