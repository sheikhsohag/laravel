<?php 

namespace App\Http\Controllers\webView;
use App\Http\Controllers\Controller;

class ExtendFunctionalityController extends Controller
{
    public function index(){
        $data = [
            "name"=>"sohag",
            "phone"=>"123456789",
            "email"=>"sohag@gmail.com"
        ];
        return view('webView.ExtendAndComponent', ["data"=>$data]);
    }
}