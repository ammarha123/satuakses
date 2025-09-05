<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSubmodule extends Model
{
    protected $fillable = [
        'course_module_id','title','content','video_url','attachment_path','sort_order'
    ];

    public function modules() { return $this->hasMany(CourseModule::class)->orderBy('sort_order'); }
    public function quizzes() { return $this->hasMany(Quiz::class); }
}