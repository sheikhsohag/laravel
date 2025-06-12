<?php 

namespace App\Http\Controllers\webView;
use App\Http\Controllers\Controller;

class ExtendFunctionalityController extends Controller
{
    public function index(){
        return view('webView.ExtendAndComponent');
    }
}