<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['course_id','title','time_limit'];
    public function course(){ return $this->belongsTo(Course::class); }
    public function questions(){ return $this->hasMany(QuizQuestion::class); }
    public function attempts(){ return $this->hasMany(\App\Models\QuizAttempt::class); }

}