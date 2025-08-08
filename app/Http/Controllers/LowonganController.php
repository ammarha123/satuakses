<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    public function index()
    {
        $lowongans = Lowongan::latest('waktu_posting')->take(6)->get();

        return view('index', compact('lowongans'));
    }
}
