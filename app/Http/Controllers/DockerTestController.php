<?php

namespace App\Http\Controllers;

use App\Models\DockerTest;
use Illuminate\Http\Request;

class DockerTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DockerTest::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $dockerTest = DockerTest::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json($dockerTest, 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DockerTest $dockerTest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DockerTest $dockerTest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DockerTest $dockerTest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DockerTest $dockerTest)
    {
        //
    }
}
