<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; // Obligatoire pour parler à la BDD

class TestController extends Controller
{
    public function index()
    {
        // On récupère toutes les lignes de ta table 'classe'
        $classes = DB::table('classe')->get();

        // On envoie ces données à la vue 'test'
        return view('test', compact('classes'));
    }
}