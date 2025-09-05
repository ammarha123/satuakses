<?php
// app/Models/CourseSubmoduleProgress.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSubmoduleProgress extends Model
{
    protected $table = 'course_submodule_progress';
    protected $fillable = ['user_id','course_id','module_id','submodule_id','completed_at'];
    protected $casts = ['completed_at' => 'datetime'];
}
