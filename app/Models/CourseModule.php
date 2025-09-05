<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    protected $fillable = ['course_id','title','summary','video_url','sort_order'];

    public function course(){ return $this->belongsTo(Course::class); }
    public function attachments(){ return $this->hasMany(ModuleAttachment::class, 'module_id'); }

    public function submodules()
    {
        return $this->hasMany(CourseSubmodule::class)->orderBy('sort_order');
    }
}
