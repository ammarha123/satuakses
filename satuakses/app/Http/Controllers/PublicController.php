<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lowongan;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $lowongans = Lowongan::latest()->take(6)->get();
        $courses = Course::latest()->take(6)->get();

        return view('index', compact('lowongans', 'courses'));
    }
}
