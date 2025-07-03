<?php 
namespace App\Http\Controllers\webView;

use App\Http\Controllers\Controller;

class AlpineJsController extends Controller
{
    public function index(){
        $data = [
            "name"=>"md sohag ali",
            "email"=>"sohag@gmail.com"
        ];

        return view('webView.alpine', ["data"=>$data]);
    }
}`