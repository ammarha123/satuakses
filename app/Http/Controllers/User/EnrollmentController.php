<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(Course $course)
    {
        Enrollment::firstOrCreate([
            'course_id' => $course->id,
            'user_id'   => Auth::id(),
        ]);

        return back()->with('success', 'Kamu bergabung ke kursus.');
    }

    public function destroy(Course $course)
    {
        Enrollment::where('course_id',$course->id)->where('user_id',Auth::id())->delete();
        return back()->with('success','Kamu keluar dari kursus.');
    }
}
