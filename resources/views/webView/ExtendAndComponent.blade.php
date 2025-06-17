@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
    <div class="relative w-full bg-red-500">
        <h1 class="absolute right-0 top-0 p-2 transform -translate-y-full">Welcome to my website!</h1>
    </div>
    <div></div>
    <p class="p-2 bg-green-700">This is the home page content.</p>

    <p class="text-red-700">my name is: {{$data['name']}}</p>

    <div>
        <x-initialComponent :data="$data" />
    </div>

    <div>
        <x-secondPara :data="$data"/>
    </div>
@endsection