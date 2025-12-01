<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DockerController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Hello from Docker!'
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'message' => 'Showing Docker item ' . $id
        ]);
    }

    public function edit($id)
    {
        return response()->json([
            'message' => 'Editing Docker item ' . $id
        ]);
    }
}
