<?php

namespace App\Http\Controllers\EgerLoading;
use App\Http\Controllers\Controller;
use App\Models\User;

class EgerLoading extends Controller
{
    public function index(){
        $user = User::select('id','name', 'email')->with('category','product')->get();
        
        foreach($user as $u){
            return response()->json()
        }
        return response()->json($user);
    }
}