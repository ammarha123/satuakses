<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizOption extends Model
{
    protected $fillable = ['question_id','text','is_correct'];
    public function question(){ return $this->belongsTo(QuizQuestion::class, 'question_id'); }
}
