<?php

namespace App\Http\Controllers\Macro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MacroController extends Controller
{
    public function index()
    {
        // Example usage of the average macro
        $numbers = [1, 2, 3, 4, 5];
        $average = collect($numbers)->average();

        return response()->json([
            'numbers' => $numbers,
            'average' => $average,
        ]);
    }
}
