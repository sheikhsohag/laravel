<?php

namespace App\Http\Controllers;

use App\Mail\CustomMessageMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendMail(Request $request){
        $validated = $request->validate([
            'message'=>'required|string',
            'email'=>'required|email'
        ]);

        Mail::to($request->input('email'))->send(new CustomMessageMail($request->input('message')));

        return response()->json([
            'success' => true,
            'message' => 'Emails sent successfully',
            'data' => null
        ]);
    }
}