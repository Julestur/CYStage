<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        $classes = DB::table('classe')->get();

        return view('test', compact('classes'));
    }
}
