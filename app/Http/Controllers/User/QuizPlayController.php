<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizPlayController extends Controller
{
    public function show(Quiz $quiz)
    {
        $quiz->load('questions.options');
        return view('quiz.play', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $request->validate([
            'answers' => 'required|array', 
        ]);

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => Auth::id(),
            'started_at' => now(),
            'finished_at' => now(),
        ]);

        $total = $quiz->questions()->count();
        $correct = 0;

        foreach ($request->answers as $questionId => $optionId) {
            $isCorrect = $quiz->questions()->where('id',$questionId)
                ->first()->options()->where('id',$optionId)->value('is_correct') ?? false;

            QuizAnswer::create([
                'attempt_id'  => $attempt->id,
                'question_id' => $questionId,
                'option_id'   => $optionId,
                'is_correct'  => $isCorrect,
            ]);

            if ($isCorrect) $correct++;
        }

        $attempt->update([
            'score' => $total ? intval(($correct / $total) * 100) : 0,
        ]);

        return redirect()->back()->with('success', 'Nilai kamu: '.$attempt->score);
    }
}
